<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Module\Video\Domain\Models\LogModel;
use Module\Video\Domain\Models\VideoModel;

class VideoRepository {

    public static function getList(string $keywords = '', int $user = 0, int $music = 0) {
        return VideoModel::with('user', 'music')->when(!empty($keywords), function ($query) {
            VideoModel::searchWhere($query, ['content']);
        })->when(!empty($user), function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when(!empty($music), function ($query) use ($music) {
            $query->where('music_id', $music);
        })->page();
    }

    /**
     * 无尽模式
     * @param string $keywords
     * @param int $user
     * @param int $music
     * @return mixed
     */
    public static function moreList(string $keywords = '', int $user = 0, int $music = 0) {
        return VideoModel::with('user', 'music')->when(!empty($keywords), function ($query) {
            VideoModel::searchWhere($query, ['content']);
        })->when(!empty($user), function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when(!empty($music), function ($query) use ($music) {
            $query->where('music_id', $music);
        })->page();
    }

    public static function get(int $id) {
        $model = VideoModel::findOrThrow($id, '数据有误');
        $model->user;
        $model->music;
        return $model;
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

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = $id > 0 ? VideoModel::findWithAuth($id) : new VideoModel();
        if (empty($model)) {
            throw new \Exception('视频不存在');
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function likeLog(int $id) {
        if (auth()->guest()) {
            throw new \Exception('请先登录', 401);
        }
        return LogModel::where('item_type', LogModel::TYPE_VIDEO)
            ->where('action', LogModel::ACTION_LIKE)
            ->where('user_id', auth()->id())
            ->where('item_id', $id)->first();
    }

    public static function isLiked(int $id) {
        if (auth()->guest()) {
            return false;
        }
        return LogModel::where('item_type', LogModel::TYPE_VIDEO)
            ->where('action', LogModel::ACTION_LIKE)
            ->where('user_id', auth()->id())
            ->where('item_id', $id)->count() > 0;
    }

    public static function like(int $id) {
        $model = VideoModel::findOrThrow($id, '视频不存在');
        $log = static::likeLog($id);
        if (empty($log)) {
            LogModel::createOrThrow([
                'item_type' => LogModel::TYPE_VIDEO,
                'item_id' => $id,
                'user_id' => auth()->id(),
                'action' => LogModel::ACTION_LIKE,
            ]);
            $model->like_count ++;

        } else {
            $log->delete();
            $model->like_count --;
        }
        $model->save();
        return $model;
    }

    public static function removeBySelf(int $id) {
        $model = VideoModel::findWithAuth($id);
        if (empty($model)) {
            throw new \Exception('无权删除此视频');
        }
        $model->delete();
    }
}