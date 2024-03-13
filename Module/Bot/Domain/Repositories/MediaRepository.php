<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Bot\Domain\Model\MediaModel;

class MediaRepository {

    public static function getList(int $bot_id, string $keywords = '', string $type = '') {
        AccountRepository::isSelf($bot_id);
        return MediaModel::where('bot_id', $bot_id)
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')
            ->orderBy('id', 'desc')->page();
    }

    public static function manageList(int $bot_id = 0, string $keywords = '', string $type = '') {
        return MediaModel::when($bot_id > 0, function ($query) use ($bot_id) {
                $query->where('bot_id', $bot_id);
            })
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')
            ->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return MediaModel::findOrThrow($id, '资源不存在');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->bot_id);
        return $model;
    }

    public static function remove(int $id) {
        $model = MediaModel::findOrThrow($id);
        AccountRepository::isSelf($model->bot_id);
        if ($model->media_id && $model->material_type == MediaModel::MATERIAL_PERMANENT) {
            BotRepository::entry($model->bot_id)->deleteMedia($model->media_id);
        }
        $model->delete();
    }

    public static function save(int $bot_id, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['bot_id']);
        if ($id > 0) {
            $model = static::getSelf($id);
        } else {
            $model = new MediaModel();
            $model->bot_id = $bot_id;
            AccountRepository::isSelf($model->bot_id);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function async(int $id) {
        $model = MediaModel::find($id);
        AccountRepository::isSelf($model->bot_id);
        if ($model->media_id &&
            ($model->material_type == MediaModel::MATERIAL_PERMANENT || $model->expired_at > time())) {
            throw new \Exception('不能重复创建');
        }
        $adapter = BotRepository::entry($model->bot_id);
        if ($model->type === MediaModel::TYPE_NEWS) {
            $adapter->pushNews($model);
            return;
        }
        $adapter->pushMedia($model);
    }


    /**
     * 转化为本地路径
     * @param string $path
     * @return \Zodream\Disk\File
     */
    public static function formatFile(string $path) {
        return public_path(strpos($path, '://') ? parse_url($path, PHP_URL_PATH) : $path);
    }


    public static function search(int $bot_id, string $keywords = '', string $type = '', int|array $id = 0) {
        AccountRepository::isSelf($bot_id);
        return MediaModel::where('bot_id', $bot_id)
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    $query->whereIn('id', $id);
                    return;
                }
                $query->where('id', $id);
            })->select('id', 'title', 'type', 'thumb', 'created_at')->page();
    }

    /**
     * 拉取全部素材
     * @param int $bot_id
     * @return void
     */
    public static function pull(int $bot_id, string $type = '') {
        set_time_limit(0);
        $adapter = BotRepository::entry($bot_id);
        if (!empty($type)) {
            $adapter->pullMedia($type);
            return;
        }
        $adapter->pullMedia(MediaModel::TYPE_IMAGE);
        $adapter->pullMedia(MediaModel::TYPE_VIDEO);
        $adapter->pullMedia(MediaModel::TYPE_VOICE);
        $adapter->pullMedia(MediaModel::TYPE_NEWS);
    }

}