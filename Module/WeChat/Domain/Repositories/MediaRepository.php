<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Html\Page;
use Zodream\ThirdParty\WeChat\Media;

class MediaRepository {

    public static function getList(int $wid, string $keywords = '', string $type = '') {
        AccountRepository::isSelf($wid);
        return MediaModel::where('wid', $wid)
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
        AccountRepository::isSelf($model->wid);
        return $model;
    }

    public static function remove(int $id) {
        $model = MediaModel::find($id);
        AccountRepository::isSelf($model->wid);
        if ($model->media_id && $model->material_type == MediaModel::MATERIAL_PERMANENT) {
            WeChatModel::find($model->wid)
                ->sdk(Media::class)->deleteMedia($model->media_id);
        }
        $model->delete();
    }

    public static function save(int $wid, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['wid']);
        if ($id > 0) {
            $model = static::getSelf($id);
        } else {
            $model = new MediaModel();
            $model->wid = $wid;
            AccountRepository::isSelf($model->wid);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function async(int $id) {
        $model = MediaModel::find($id);
        AccountRepository::isSelf($model->wid);
        if ($model->media_id &&
            ($model->material_type == MediaModel::MATERIAL_PERMANENT || $model->expired_at > time())) {
            throw new \Exception('不能重复创建');
        }
        if (!$model->async(WeChatModel::find($model->wid)
            ->sdk(Media::class))) {
            throw new \Exception('创建失败');
        }
    }

    public static function search(int $wid, string $keywords = '', string $type = '', int|array $id = 0) {
        AccountRepository::isSelf($wid);
        return MediaModel::where('wid', $wid)
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
}