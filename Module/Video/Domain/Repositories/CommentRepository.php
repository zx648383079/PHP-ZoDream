<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Module\Video\Domain\Models\CommentModel;
use Module\Video\Domain\Models\VideoModel;

class CommentRepository {

    public static function getList(string $keywords = '', int $video = 0, int $user = 0) {
        return CommentModel::with('user')->when(!empty($keywords), function ($query) {
            CommentModel::searchWhere($query, ['content']);
        })->when($video > 0, function ($query) use ($video) {
            $query->where('video_id', $video);
        })->when(!empty($user), function ($query) use ($user) {
            $query->where('user_id', $user);
        })->page();
    }

    public static function getAllList(string $keywords = '', int $video = 0, int $user = 0) {
        return CommentModel::with('user', 'replies')->when(!empty($keywords), function ($query) {
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

    public static function save(array $data) {
        $model = new CommentModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        VideoModel::query()->where('id', $model->id)
            ->updateOne('comment_count');
        return $model;
    }

    public static function removeBySelf(int $id) {
        $model = CommentModel::findWithAuth($id);
        if (empty($model)) {
            throw new \Exception('无权删除此评论');
        }
        $model->delete();
    }
}