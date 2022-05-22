<?php
declare(strict_types=1);
namespace Module\Video\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\TagProvider;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Video\Domain\Models\VideoModel;

final class VideoRepository {

    const LOG_TYPE_VIDEO = 7;
    const LOG_ACTION_LIKE = 1;
    const BASE_KEY = 'video';

    public static function comment(): CommentProvider {
        return new CommentProvider(self::BASE_KEY);
    }

    public static function log(): ActionLogProvider {
        return new ActionLogProvider(self::BASE_KEY);
    }

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

    public static function getList(string $keywords = '', int $user = 0, int $music = 0, $id = null) {
        return VideoModel::with('user', 'music')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['content']);
        })->when(!empty($user), function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when(!empty($music), function ($query) use ($music) {
            $query->where('music_id', $music);
        })->when(!empty($id), function ($query) use ($id) {
            $query->whereIn('id', (array)$id);
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
            SearchModel::searchWhere($query, ['content']);
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
        $id = $data['id'] ?? 0;
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

    public static function isLiked(int $id) {
        return static::log()->has(self::LOG_TYPE_VIDEO, self::LOG_ACTION_LIKE, $id);
    }

    public static function like(int $id) {
        $model = VideoModel::findOrThrow($id, '视频不存在');
        $res = static::log()->toggleLog(self::LOG_TYPE_VIDEO, self::LOG_ACTION_LIKE, $id);
        if ($res > 0) {
            $model->like_count ++;
        } else {
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

    public static function user(int $id): array {
        $user = UserSimpleModel::findOrThrow($id, '用户不存在');
        $data = $user->toArray();
        $res = VideoModel::query()->where('user_id', $id)
            ->asArray()
            ->first('COUNT(id) as video_count, SUM(like_count) as like_count');
        foreach ($res as $k => $v) {
            $data[$k] = intval($v);
        }
        return $data;
    }

    public static function addTag(int $video, string|array $tags) {
        static::tag()->bindTag($video, $tags);
    }

    public static function searchVideoTag(string $keywords): array {
        return static::tag()->searchTag($keywords);
    }
}