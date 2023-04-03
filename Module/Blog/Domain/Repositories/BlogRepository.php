<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Repositories\LocalizeRepository;
use Exception;
use Infrastructure\HtmlExpand;
use Module\Auth\Domain\FundAccount;
use Module\Blog\Domain\Helpers\Html;
use Module\Blog\Domain\Model\BlogClickLogModel;
use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Model\TagModel;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Helpers\Time;
use Zodream\Html\Page;

class BlogRepository {

    /**
     * @param string|array $sort
     * @param int $category
     * @param string $keywords
     * @param int $user
     * @param string $language
     * @param string $programming_language
     * @param string $tag
     * @param int $per_page
     * @return Page<BlogModel>
     * @throws Exception
     */
    public static function getList(string|array $sort = 'new', int $category = 0, string $keywords = '',
                                   int $user = 0, string $language = '', string $programming_language = '',
                                   string $tag = '', int $per_page = 20) {
        /** @var Page $page */
        $page = self::bindQuery(BlogPageModel::with('term', 'user'),
            $sort, $category, $keywords,
            $user, $language, $programming_language,
            $tag)->page($per_page);
        $items = LocalizeRepository::formatList($page, BlogPageModel::with('term', 'user'));
        return $page->setPage($items);
    }


    public static function getSimpleList(string|array $sort = 'new', int $category = 0, string $keywords = '',
                                   int $user = 0, string $language = '', string $programming_language = '',
                                   string $tag = '', int $limit = 5) {
        /** @var Page $page */
        $page = self::bindQuery(BlogSimpleModel::query(), $sort, $category, $keywords,
            $user, $language, $programming_language,
            $tag)
            ->limit($limit ?? 5)->all();
        return LocalizeRepository::formatList($page, BlogSimpleModel::query());
    }

    /**
     * @param SqlBuilder $query
     * @param string|array $sort
     * @param int $category
     * @param string $keywords
     * @param int $user
     * @param string $language
     * @param string $programming_language
     * @param string $tag
     * @return SqlBuilder
     */
    private static function bindQuery(SqlBuilder $query, string|array $sort = 'new', int $category = 0,
                                      string $keywords = '',
                                      int $user = 0, string $language = '',
                                      string $programming_language = '',
                                      string $tag = '') {
        return $query->where('publish_status', PublishRepository::PUBLISH_STATUS_POSTED)
            ->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', $category);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if (is_array($sort)) {
                    // 增加直接放id
                    return $query->whereIn('id', $sort)->orderBy('created_at', 'desc');
                }
                if ($sort === 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort === 'recommend' || $sort === 'best') {
                    return $query->orderBy('recommend_count', 'desc');
                }
                if ($sort === 'hot') {
                    return $query->orderBy('comment_count', 'desc');
                }
                return $query;
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title', 'programming_language']);
            })->when(!empty($language), function ($query) use ($language) {
                $query->where('language', $language);
            }, function ($query) {
                $query->where('parent_id', 0);
            })->when(!empty($programming_language), function ($query) use ($programming_language) {
                $query->where('programming_language', $programming_language);
            })->when(!empty($tag), function ($query) use ($tag) {
                $ids = TagModel::getBlogByName($tag);
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $ids);
            });
    }

    /**
     * 获取最新文章
     * @param int $limit
     * @return BlogSimpleModel[]
     */
    public static function getNew(int $limit = 5) {
        return self::getSimpleList('new',
            0, '', 0, '', '', '', $limit);
    }
    /**
     * 获取热门文章
     * @param int $limit
     * @return BlogSimpleModel[]
     */
    public static function getHot(int $limit = 5) {
        return self::getSimpleList('hot',
            0, '', 0, '', '', '', $limit);
    }
    /**
     * 获取推荐文章
     * @param int $limit
     * @return BlogSimpleModel[]
     */
    public static function getBest(int $limit = 5) {
        return self::getSimpleList('best',
            0, '', 0, '', '', '', $limit);
    }

    public static function getArchives() {
        $data = [];
        $items = LocalizeRepository::formatList(
            BlogModel::query()->where('parent_id', 0)
                ->orderBy('created_at', 'desc')
                ->asArray()->get('id', 'title', 'parent_id', 'created_at'),
            BlogModel::query()->asArray()
            ->select(['id', 'title', 'parent_id', 'created_at'])
        );
        foreach ($items as $item) {
            $year = Time::format($item['created_at'], 'Y');
            if (!isset($data[$year])) {
                $data[$year] = [
                    'year' => $year,
                    'children' => []
                ];
            }
            $item['date'] = Time::format($item['created_at'], 'm-d');
            $data[$year]['children'][] = $item;
        }
        return array_values($data);
    }

    /**
     * 自动判断是否延迟加载图片并缓存内容
     * @param BlogModel $blog
     * @return string
     * @throws Exception
     */
    public static function renderContent(BlogModel $blog) {
        $isPlatform = Platform::isPlatform();
        return static::renderLazyContent($blog, !$isPlatform
            // && Bot::isSpider()
            , $isPlatform);
    }

    /**
     * 缓存博客内容编译
     * @param BlogModel $blog
     * @param bool $imgLazy 是否延迟加载图片
     * @param bool $useDeeplink 是否使用深度链接
     * @return string
     * @throws Exception
     */
    public static function renderLazyContent(BlogModel $blog, bool $imgLazy = false, bool $useDeeplink = false) {
        $cb = function () use ($blog, $imgLazy, $useDeeplink) {
            return Html::render($blog->getAttributeSource('content'),
                TagRepository::getTags($blog->id), $blog->edit_type == 1, $imgLazy, $useDeeplink);
        };
        if (app()->isDebug()) {
            return $cb();
        }
        return cache()->store('pages')
            ->getOrSet(sprintf('blog_%d_%d_%d_content', $blog->id, $imgLazy, $useDeeplink), $cb, 3600);
    }

    /**
     * 显示在前台
     * @param int $id
     * @return array
     * @throws Exception
     */
    public static function detail(int $id, string $open_key = '') {
        static::addClick($id);
        list($blog, $readRole) = self::getWithRole($id, $open_key);
        $data = self::formatBody($blog, $readRole > 1);
        $data = array_merge($data, static::renderAsset(BlogMetaModel::getOrDefault($id)));
        $data['comment_status'] = CommentRepository::commentStatus($data['comment_status']);
        $data['previous'] = self::previous($blog->id, $blog->language);
        $data['next'] = self::next($blog->id, $blog->language);
        $data['languages'] = BlogRepository::languageList($blog->parent_id > 0 ? $blog->parent_id : $blog->id);
        return $data;
    }

    /**
     * 获取主题内容
     * @param int $id
     * @param string $openKey
     * @return array
     * @throws Exception
     */
    public static function detailBody(int $id, string $openKey = ''): array {
        list($blog, $readRole) = self::getWithRole($id, $openKey);
        return self::formatBody($blog, $readRole > 1);
    }

    private static function formatBody(BlogModel $blog, bool $canRead) {
        $data = $blog->toArray();
        $data['can_read'] = $canRead;
        if ($blog->open_type === PublishRepository::OPEN_BUY) {
            $data['open_rule'] = intval($blog->open_rule);
        }
        if ($canRead) {
            $data['content'] = BlogRepository::renderContent($blog);
        } else {
            $data['content'] = HtmlExpand::substr(BlogRepository::renderContent($blog), 50);
        }
        return $data;
    }

    /**
     * 获取内容并获取对应权限
     * @param int $id
     * @param string $openKey
     * @return array{BlogModel, int}
     * @throws Exception
     */
    public static function getWithRole(int $id, string $openKey = ''): array {
        /** @var BlogModel $blog */
        $blog = BlogModel::where('id', $id)
            ->first();
        if (empty($blog)) {
            throw new Exception(__('blog is not exist'));
        }
        $readRole = self::readRole($blog, $openKey);
        if ($readRole < 1) {
            throw new Exception(__('blog is not exist'));
        }
        return [$blog, $readRole];
    }

    /**
     * 确认操作并查看全部内容
     * @param int $id
     * @param string $openKey
     * @return array
     * @throws Exception
     */
    public static function detailOpen(int $id, string $openKey = ''): array {
        /** @var BlogModel $blog */
        $blog = BlogModel::where('id', $id)
            ->first();
        if (empty($blog)) {
            throw new Exception(__('blog is not exist'));
        }
        if (!auth()->guest() && $blog->user_id === auth()->id()) {
            return self::formatBody($blog, true);
        }
        if ($blog->publish_status !== PublishRepository::PUBLISH_STATUS_POSTED) {
            throw new Exception(__('blog is not exist'));
        }
        if ($blog->open_type < 1) {
            return self::formatBody($blog, true);
        }
        if ($blog->open_type === PublishRepository::OPEN_LOGIN) {
            if (auth()->guest()) {
                throw new Exception(__('Please Login User!'), 401);
            }
            return self::formatBody($blog, true);
        }
        if ($blog->open_type === PublishRepository::OPEN_PASSWORD) {
            if ($openKey !== $blog->open_rule) {
                throw new Exception(__('password is error'));
            }
            if (!auth()->guest()) {
                LogRepository::logOnly(BlogLogModel::TYPE_BLOG,
                    BlogLogModel::ACTION_REAL_RULE, $blog->id);
            }
            return self::formatBody($blog, true);
        }
        if ($blog->open_type === PublishRepository::OPEN_BUY) {
            if (auth()->guest()) {
                throw new Exception(__('Please Login User!'), 401);
            }
            if (LogRepository::has(auth()->id(), BlogLogModel::TYPE_BLOG,
                BlogLogModel::ACTION_REAL_RULE, $blog->id)) {
                return self::formatBody($blog, true);
            }
            $res = FundAccount::change(
                auth()->id(), FundAccount::TYPE_BUY_BLOG,
                $blog->id, intval($blog->open_rule), '购买文章阅读权限');
            if (!$res) {
                throw new Exception(__('Low account balance'));
            }
            BlogLogModel::create([
                'user_id' => auth()->id(),
                'item_type' => BlogLogModel::TYPE_BLOG,
                'item_id' => $blog->id,
                'action' => BlogLogModel::ACTION_REAL_RULE
            ]);
            return self::formatBody($blog, true);
        }
        throw new Exception(__('unknown'));
    }

    public static function previous(int $id, string $language) {
        return BlogSimpleModel::where('id', '<', $id)
            ->where('language', $language)
            ->where('publish_status', PublishRepository::PUBLISH_STATUS_POSTED)
            ->orderBy('id', 'desc')
            ->first();
    }

    public static function next(int $id, string $language) {
        return BlogSimpleModel::where('id', '>', $id)
            ->where('language', $language)
            ->where('publish_status', PublishRepository::PUBLISH_STATUS_POSTED)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * 是否能够阅读详细内容
     * @param BlogModel $model
     * @param string $openKey
     * @return int // 0 不显示  // 1 不完全显示，需要购买或登录 // 2完全显示
     * @throws Exception
     */
    public static function readRole(BlogModel $model, string $openKey = ''): int {
        if (!auth()->guest() && $model->user_id === auth()->id()) {
            return 2;
        }
        if ($model->publish_status !== PublishRepository::PUBLISH_STATUS_POSTED) {
            return 0;
        }
        if ($model->open_type < 1) {
            return 2;
        }
        if ($model->open_type === PublishRepository::OPEN_LOGIN) {
            return auth()->guest() ? 1 : 2;
        }
        if ($model->open_type === PublishRepository::OPEN_PASSWORD) {
            if (auth()->guest()) {
                return $model->open_rule === $openKey ? 2 : 1;
            }
            return BlogLogModel::where([
                    'user_id' => auth()->id(),
                    'item_type' => BlogLogModel::TYPE_BLOG,
                    'item_id' => $model->id,
                    'action' => BlogLogModel::ACTION_REAL_RULE
                ])->count() > 1 ? 2 : 1;
        }
        if ($model->open_type === PublishRepository::OPEN_BUY) {
            if (auth()->guest()) {
                return 1;
            }
            return BlogLogModel::where([
                    'user_id' => auth()->id(),
                    'item_type' => BlogLogModel::TYPE_BLOG,
                    'item_id' => $model->id,
                    'action' => BlogLogModel::ACTION_REAL_RULE
                ])->count() > 0 ? 2 : 1;
        }
        return 1;
    }

    protected static function renderAsset(array $data): array {
        foreach ([
                     'audio_url',
                     'video_url',
                 ] as $key) {
            if (isset($data[$key]) && !empty($data[$key])) {
                $data[$key] = url()->asset($data[$key]);
            }
        }
        return $data;
    }

    public static function isSelf(int $id): bool {
        return  BlogModel::where('id', $id)->where('user_id', auth()->id())
            ->count() > 0;
    }

    /**
     * 获取已有语言列表，显示在前台
     * @param int $id
     * @param bool $auto
     * @return array|array[]
     */
    public static function languageList(int $id, bool $auto = false) {
        if ($auto) {
            $parent = BlogModel::where('id', $id)->value('parent_id');
            if ($parent > 0) {
                $id = $parent;
            }
        }
        $languages = BlogModel::where('parent_id', $id)->asArray()->get('id', 'language');
        $languages[] = [
            'id' => $id,
            'language' => LocalizeRepository::firstLanguage()
        ];
        return LocalizeRepository::formatLanguageList($languages, true);
    }



    public static function addClick(int $blogId, int $amount = 1) {
        BlogModel::where('id', $blogId)->updateIncrement('click_count', $amount);
        $day = date('Y-m-d');
        $log = BlogClickLogModel::where('happen_day', $day)->where('blog_id', $blogId)
            ->first();
        if (empty($log)) {
            BlogClickLogModel::create([
                'happen_day' => $day,
                'blog_id' => $blogId,
                'click_count' => $amount,
            ]);
            return;
        }
        BlogClickLogModel::query()->where('happen_day', $day)->where('blog_id', $blogId)
            ->updateIncrement('click_count', $amount);
    }

    public static function getStatistics(int $blog): array {
        return BlogModel::query()->where('id', $blog)->asArray()
            ->first('click_count', 'recommend_count', 'comment_count');
    }

    public static function recommend(int $id) {
        $model = BlogModel::findOrThrow($id, __('blog is not exist'));
        $res = LogRepository::toggleLog(BlogLogModel::TYPE_BLOG, BlogLogModel::ACTION_RECOMMEND, $id);
        $model->recommend_count += $res > 0 ? 1 : -1;
        $model->save();
        return $model;
    }

    public static function canComment(int $id): bool {
        $val = CommentRepository::blogCommentStatus($id);
        if ($val === 2 && auth()->guest()) {
            return false;
        }
        return $val > 0;
    }

    public static function getLogList(int $blog) {
        return BlogLogModel::with('user', 'blog')
            ->where('item_id', $blog)
            ->where('item_type', BlogLogModel::TYPE_BLOG)
            ->orderBy('created_at desc')
            ->page();
    }

    /**
     * 是否能推荐
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public static function hasLog(int $id, int $action): bool {
        if (auth()->guest()) {
            return false;
        }
        return !LogRepository::has(auth()->id(), BlogLogModel::TYPE_BLOG, $action, $id);
    }

    public static function getPostCount(int $user): int {
        return BlogModel::where('user_id', $user)->count();
    }
}