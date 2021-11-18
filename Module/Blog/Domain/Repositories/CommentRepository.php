<?php
namespace Module\Blog\Domain\Repositories;

use Domain\Constants;
use Domain\Model\SearchModel;
use Infrastructure\LinkRule;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Auth\Domain\Repositories\BulletinRepository;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentFullModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\CommentPageModel;
use Module\Contact\Domain\Repositories\ReportRepository;
use Module\SEO\Domain\Repositories\EmojiRepository;
use Zodream\Html\Page;
use Exception;

class CommentRepository {

    /**
     * @param int $blog_id
     * @param int $parent_id
     * @param bool $is_hot
     * @param string $sort
     * @param string $order
     * @param int $per_page
     * @return Page<CommentPageModel>
     */
    public static function getList(int $blog_id, int $parent_id = 0, bool $is_hot = false, string $sort = 'created_at',
                                    string $order = 'desc', int $per_page = 20) {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['created_at', 'id', 'agree_count']);
        return CommentPageModel::with('replies')
            ->where([
                'blog_id' => $blog_id,
                'parent_id' => $parent_id
            ])->when($is_hot, function ($query) {
                $query->where('agree_count', '>', 0)->orderBy('agree_count desc');
            })->orderBy($sort, $order)
            ->page($per_page);
    }

    public static function create(array $data) {
        if (!BlogModel::canComment($data['blog_id'])) {
            throw new Exception('不允许评论！');
        }
        if (!auth()->guest()) {
            $data['user_id'] = auth()->id();
            $data['name'] = auth()->user()->name;
        }
        $parentId = intval($data['parent_id']);
        if ($parentId > 0) {
            $parent = CommentModel::findOrThrow($parentId);
            if ($parent->parent_id > 0) {
                $parent = CommentModel::findOrThrow($parent->parent_id);
            }
            $parentId = $parent->id;
        }
        $last = CommentModel::where('blog_id', $data['blog_id'])
            ->where('parent_id', $parentId)
            ->orderBy('position desc')->first();
        $data['parent_id'] = $parentId;
        $data['position'] = empty($last) ? 1 : ($last->position + 1);
        $comment = CommentModel::createOrThrow($data);
        BlogModel::where('id', $data['blog_id'])->updateIncrement('comment_count');
        $extraRules = array_merge([
            self::at($data['content'], $data['blog_id'], $parentId),
            EmojiRepository::renderRule($data['content'])
        ]);
        if (!empty($extraRules)) {
            $comment->extra_rule = $extraRules;
            $comment->save();
        }
        return $comment;
    }

    public static function at(string $content, int $blogId, int $commentId): array {
        if (empty($content) || !str_contains($content, '@')) {
            return [];
        }
        if (!preg_match_all('/@(\S+?)\s/', $content, $matches, PREG_SET_ORDER)) {
            return [];
        }
        $names = [];
        $commentPosition = [];
        foreach ($matches as $match) {
            if (preg_match('/^\d+#$/', $match[1])) {
                $commentPosition[substr($match[1], 0, -1)] = $match[0];
                continue;
            }
            $names[$match[1]] = $match[0];
        }
        return array_merge(static::atUser($names, $blogId), static::atPosition($commentPosition, $commentId));
    }

    protected static function atPosition(array $items, int $commentId) {
        if ($commentId < 1) {
            return [];
        }
        $commentItems = CommentModel::whereIn('position', array_keys($items))->where('parent_id', $commentId)
            ->asArray()->get('id', 'position');
        if (empty($commentItems)) {
            return [];
        }
        $rules = [];
        foreach ($commentItems as $item) {
            $rules[] = LinkRule::formatId($items[$item['position']], 'comment-'. $item['id']);
        }
        return $rules;
    }

    protected static function atUser(array $names, int $blogId) {
        if (empty($names)) {
            return [];
        }
        $users = UserSimpleModel::whereIn('name', array_keys($names))->asArray()->get();
        if (empty($users)) {
            return [];
        }
        $rules = [];
        $currentUser = auth()->id();
        $userIds = [];
        foreach ($users as $user) {
            if ($user['id'] != $currentUser) {
                $userIds[] = $user['id'];
            }
            $rules[] = LinkRule::formatUser($names[$user['name']], intval($user['id']));
        }
        if ($blogId < 1 || !empty($userIds)) {
            return $rules;
        }
        BulletinRepository::sendAt($userIds, '我在博客评论总提到了你', 'blog/'.$blogId);
        return $rules;
    }

    public static function getHot(int $blog_id, int $limit = 4) {
        return CommentModel::where([
            'blog_id' => $blog_id,
            'parent_id' => 0,
        ])->where('agree_count', '>', 0)->orderBy('agree_count desc')->limit($limit)->get();
    }

    /**
     * 用于后台管理
     * @param int $blog
     * @param string $keywords
     * @param string $email
     * @param string $name
     */
    public static function commentList(int $blog = 0, string $keywords = '', string $email = '',
                                   string $name = '') {
        return CommentFullModel::with('blog')
            ->when($blog > 0, function ($query) use ($blog) {
                $query->where('blog_id', $blog);
            })->when(!empty($email), function ($query) use ($email) {
                $query->where('email', $email);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'content');
            })->when(!empty($name), function ($query) {
                SearchModel::searchWhere($query, 'name', false, 'name');
            })->orderBy('id', 'desc')->page();
    }

    public static function remove(int $id) {
        CommentModel::where('id', $id)->delete();
    }

    /**
     * 前台删除，博主或发表人
     * @param int $id
     */
    public static function removeSelf(int $id) {
        $model = CommentModel::find($id);
        if (empty($model)) {
            throw new Exception('评论删除失败');
        }
        if ($model->user_id > 0 && $model->user_id === auth()->id()) {
            $model->delete();
            return;
        }
        if (!BlogRepository::isSelf($model->blog_id)) {
            throw new Exception('评论删除失败');
        }
        $model->delete();
    }

    public static function newList() {
        return CommentModel::with('blog')
            ->where('approved', 1)->orderBy('created_at', 'desc')->limit(4)->get();
    }

    public static function report(int $id) {
        $model = CommentModel::findOrThrow($id);
        ReportRepository::quickCreate(Constants::TYPE_BLOG_COMMENT, $id,
            sprintf('“%s”', $model->content), '举报博客评论');
    }
}