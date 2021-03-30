<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\MicroBlog\Domain\Model\CommentModel;

class CommentRepository {
    public static function getList(string $keywords = '', int $user = 0, int $micro = 0) {
        return CommentModel::with('user')
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($micro > 0, function ($query) use ($micro) {
                $query->where('micro_id', $micro);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['content']);
            })->orderBy('id', 'desc')->page();
    }

    public static function remove(int $id) {
        CommentModel::where('id', $id)->delete();
    }

    public static function commentList(int $micro, int $parent_id = 0, string $sort = 'created_at', string $order = 'desc') {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['created_at', 'id']);
        return CommentModel::with('replies', 'user')
            ->where('micro_id', $micro)
            ->where('parent_id', $parent_id)->orderBy($sort, $order)->page();
    }
}
