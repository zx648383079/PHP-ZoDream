<?php
namespace Module\Blog\Domain\Repositories;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\CommentPageModel;
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
    public static function getList($blog_id, $parent_id = 0, $is_hot = false, $sort = 'created_at',
                                   $order = 'desc', $per_page = 20) {
        list($sort, $order) = CommentPageModel::checkSortOrder($sort, $order, ['created_at', 'id', 'agree']);
        return CommentPageModel::with('replies')
            ->where([
                'blog_id' => intval($blog_id),
                'parent_id' => intval($parent_id)
            ])->when($is_hot, function ($query) {
                $query->where('agree', '>', 0)->orderBy('agree desc');
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
        $comment = CommentModel::create($data);
        if (empty($comment)) {
            throw new Exception('评论失败！');
        }
        BlogModel::where('id', $data['blog_id'])->updateOne('comment_count');
        return $comment;
    }

    public static function getHot($blog_id, $limit = 4) {
        return CommentModel::where([
            'blog_id' => intval($blog_id),
            'parent_id' => 0,
        ])->where('agree', '>', 0)->orderBy('agree desc')->limit($limit)->all();
    }
}