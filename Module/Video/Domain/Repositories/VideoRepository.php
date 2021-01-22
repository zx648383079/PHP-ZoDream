<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Module\Video\Domain\Models\VideoModel;

class VideoRepository {

    public static function getList(string $keywords = '', int $user = 0, int $music = 0) {
        return VideoModel::query()->when(!empty($keywords), function ($query) {
            VideoModel::searchWhere($query, ['content']);
        })->when(!empty($user), function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when(!empty($music), function ($query) use ($music) {
            $query->where('music_id', $music);
        })->page();
    }

    public static function changeStatus(int $id, int $status) {
        $model = VideoModel::findOrThrow($id, '视频不存在');
        $model->status = $status;
        $model->save();
        return $model;
    }

    public static function remove(int $id) {
        VideoModel::where('id', $id)->delete();
    }
}