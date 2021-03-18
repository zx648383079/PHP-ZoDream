<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Infrastructure\HtmlExpand;
use Module\Blog\Domain\Events\BlogUpdate;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Database\Model\Query;
use Zodream\Helpers\Time;
use Zodream\Html\Page;

class BlogRepository {

    /**
     * @param string $sort
     * @param null $category
     * @param null $keywords
     * @param null $user
     * @param null $language
     * @param null $programming_language
     * @param null $tag
     * @param int $per_page
     * @return Page<BlogModel>
     */
    public static function getList($sort = 'new', $category = null, $keywords = null,
                                   $user = null, $language = null, $programming_language = null,
                                   $tag = null, $per_page = 20) {
        /** @var Page $page */
        $page = self::bindQuery(BlogPageModel::with('term', 'user'),
            $sort, $category, $keywords,
            $user, $language, $programming_language,
            $tag)->page($per_page);
        $items = self::formatLanguage($page, BlogPageModel::with('term', 'user'));
        return $page->setPage($items);
    }

    public static function getSelfList(string $keywords = '', int $category = 0, int $type = 0) {
        return BlogPageModel::with('term')
            ->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', $category);
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })->orderBy('id', 'desc')->page();
    }

    public static function getSimpleList($sort = 'new', $category = null, $keywords = null,
                                   $user = null, $language = null, $programming_language = null,
                                   $tag = null, $limit = 5) {
        /** @var Page $page */
        $page = self::bindQuery(BlogSimpleModel::query(), $sort, $category, $keywords,
            $user, $language, $programming_language,
            $tag)
            ->limit($limit ?? 5)->all();
        return self::formatLanguage($page, BlogSimpleModel::query());
    }

    /**
     * @param SqlBuilder $query
     * @param string $sort
     * @param null $category
     * @param null $keywords
     * @param null $user
     * @param null $language
     * @param null $programming_language
     * @param null $tag
     * @return SqlBuilder
     */
    private static function bindQuery(SqlBuilder $query, $sort = 'new', $category = null, $keywords = null,
                                      $user = null, $language = null, $programming_language = null,
                                      $tag = null) {
        return $query->where('open_type', '<>', BlogModel::OPEN_DRAFT)
            ->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', intval($category));
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', intval($user));
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
    public static function getNew($limit = 5) {
        return self::getSimpleList('new',
            0, null, 0, null, null, null, $limit);
    }
    /**
     * 获取热门文章
     * @param int $limit
     * @return BlogSimpleModel[]
     */
    public static function getHot($limit = 5) {
        return self::getSimpleList('hot',
            0, null, 0, null, null, null, $limit);
    }
    /**
     * 获取推荐文章
     * @param int $limit
     * @return BlogSimpleModel[]
     */
    public static function getBest($limit = 5) {
        return self::getSimpleList('best',
            0, null, 0, null, null, null, $limit);
    }

    public static function getArchives() {
        $data = [];
        $items = self::formatLanguage(
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

    public static function formatLanguage($items, Query $query) {
        if (empty($items)) {
            return [];
        }
        $lang = trans()->getLanguage();
        if (stripos($lang, 'zh') !== false) {
            return is_array($items) ? $items : $items->getIterator();
        }
        $ids = [];
        foreach ($items as $item) {
            $ids[] = $item['id'];
        }
        if (empty($ids)) {
            return [];
        }
        $args = $query->whereIn('parent_id', $ids)->where('language', 'en')->get();
        $data = [];
        foreach ($args as $item) {
            $data[$item['parent_id']] = $item;
        }
        $args = [];
        foreach ($items as $item) {
            $args[] = isset($data[$item['id']]) ? $data[$item['id']] : $item;
        }
        return $args;
    }

    public static function renderContent(BlogModel $blog) {
        return cache()->store('pages')->getOrSet(sprintf('blog_%d_content', $blog->id), function () use ($blog) {
            return TagRepository::renderTags($blog->id, HtmlExpand::toHtml($blog->getAttributeValue('content'), $blog->edit_type == 1));
        }, 3600);
    }

    public static function sourceBlog(int $id, string $language = '') {
        $model = BlogModel::getOrNew($id, $language);
        if (empty($model) || (!$model->isNewRecord && $model->user_id != auth()->id())) {
            throw new Exception('博客不存在');
        }
        $tags = $model->isNewRecord ? [] : TagRepository::getTags($model->id);
        $data = $model->toArray();
        $data['tags'] = $tags;
        $data['open_rule'] = $model->open_rule;
        return array_merge($data, BlogMetaModel::getMetaWithDefault($id));
    }

    public static function save(array $data, int $id = 0) {
        unset($data['id']);
        $model = BlogModel::findOrNew($id);
        $isNew = $model->isNewRecord;
        if (!$model->load($data, ['user_id'])) {
            throw new Exception('数据有误');
        }
        // 需要同步的字段
        $async_column = [
            'user_id',
            'term_id',
            'programming_language',
            'type',
            'thumb',
            'open_type',
            'open_rule',];
        $model->user_id = auth()->id();
        if ($model->parent_id > 0) {
            $parent = BlogModel::find($model->parent_id);
            if (empty($model->language) || $model->language == 'zh') {
                $model->language = 'en';
            }
            foreach ($async_column as $key) {
                $model->{$key} = $parent->{$key};
            }
        }
        $model->parent_id = intval($model->parent_id);
        if (!$model->saveIgnoreUpdate()) {
            throw new Exception($model->getFirstError());
        }
        if ($model->parent_id < 1) {
            TagRepository::addTag($model->id,
                isset($data['tags']) && !empty($data['tags']) ? $data['tags'] : []);
            $asyncData = [];
            foreach ($async_column as $key) {
                $asyncData[$key] = $model->getAttributeSource($key);
            }
            BlogModel::where('parent_id', $model->id)->update($asyncData);
        }
        BlogMetaModel::saveMeta($model->id, $data);
        event(new BlogUpdate($model, $isNew, time()));
        return $model;
    }

    public static function remove($id) {
        $model = BlogModel::where('id', $id)->where('user_id', auth()->id())
            ->first();
        if (empty($model)) {
            throw new Exception('文章不存在');
        }
        $model->delete();
        if ($model->parent_id < 1) {
            BlogModel::where('parent_id', $id)->delete();
        }
        BlogMetaModel::deleteMeta($id);
    }

    public static function subtotal() {
        $term_count = TermModel::query()->count();
        $blog_id = BlogModel::where('user_id', auth()->id())->pluck('id');
        $blog_count = count($blog_id);
        $view_count = BlogModel::where('user_id', auth()->id())->sum('click_count');
        $comment_count = CommentModel::whereIn('blog_id', $blog_id)
            ->count();
        return [
            [
                'name' => '分类',
                'icon' => 'fa-folder',
                'count' => $term_count
            ],
            [
                'name' => '文章',
                'icon' => 'fa-file',
                'count' => $blog_count
            ],
            [
                'name' => '评论',
                'icon' => 'fa-comment',
                'count' => $comment_count
            ],
            [
                'name' => '浏览量',
                'icon' => 'fa-eye',
                'count' => $view_count
            ],
        ];
    }
}