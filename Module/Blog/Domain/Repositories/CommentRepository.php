<?php
namespace Module\Blog\Domain\Repositories;

use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\CommentPageModel;
use Zodream\Html\Page;

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
            })->orderBy($sort, $order)->page()
            ->page($per_page);
    }

    /**
     * 获取最新文章
     * @param int $limit
     * @return BlogSimpleModel[]
     */
    public static function getNew($limit = 5) {
        return BlogSimpleModel::orderBy('created_at desc')->limit($limit ?? 5)->all();
    }
    /**
     * 获取热门文章
     * @param int $limit
     * @return CommentModel[]
     */
    public static function getHot($blog_id, $limit = 5) {
        return CommentModel::where([
            'blog_id' => intval($blog_id),
            'parent_id' => 0,
        ])->where('agree', '>', 0)->orderBy('agree desc')->limit($limit ?? 5)->all();
    }
    /**
     * 获取推荐文章
     * @param int $limit
     * @return BlogSimpleModel[]
     */
    public static function getBest($limit = 5) {
        return BlogSimpleModel::orderBy('recommend desc')
            ->limit($limit ?? 5)->all();
    }
}