<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Module\Video\Domain\Models\CommentModel;

class CommentRepository {

    public static function getList(string $keywords = '', int $video = 0, int $user = 0) {
        return CommentModel::query()->when(!empty($keywords), function ($query) {
            CommentModel::searchWhere($query, ['content']);
        })->when($video > 0, function ($query) use ($video) {
            $query->where('video_id', $video);
        })->when(!empty($user), function ($query) use ($user) {
            $query->where('user_id', $user);
        })->page();
    }

    public static function remove(int $id) {
        CommentModel::where('id', $id)->orWhere('parent_id', $id)->delete();
    }
}