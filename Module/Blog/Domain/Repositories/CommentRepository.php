<?php
namespace Module\Blog\Domain\Repositories;

use Domain\Constants;
use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentFullModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\CommentPageModel;
use Module\Contact\Domain\Repositories\ReportRepository;
use Zodream\Html\Page;
use Exception;

class CommentRepository {

    /**
     * @param $blog_id
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
        $data['parent_id'] = intval($data['parent_id']);
        $last = CommentModel::where('blog_id', $data['blog_id'])->where('parent_id', $data['parent_id'])->orderBy('position desc')->one();
        $data['position'] = empty($last) ? 1 : ($last->position + 1);
        $comment = CommentModel::createOrThrow($data);
        BlogModel::where('id', $data['blog_id'])->updateIncrement('comment_count');
        return $comment;
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