<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\Media;

class MediaRepository {

    public static function getList(int $wid, string $keywords = '', string $type = '') {
        return MediaModel::where('wid', $wid)
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb')->page();
    }

    public static function get(int $id) {
        return MediaModel::findOrThrow($id, '资源不存在');
    }

    public static function remove(int $id) {
        $model = MediaModel::find($id);
        if ($model->media_id && $model->material_type == MediaModel::MATERIAL_PERMANENT) {
            WeChatModel::find($model->wid)
                ->sdk(Media::class)->deleteMedia($model->media_id);
        }
        $model->delete();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = MediaModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function async(int $id) {
        $model = MediaModel::find($id);
        if ($model->media_id &&
            ($model->material_type == MediaModel::MATERIAL_PERMANENT || $model->expired_at > time())) {
            throw new \Exception('不能重复创建');
        }
        if (!$model->async(WeChatModel::find($model->wid)
            ->sdk(Media::class))) {
            throw new \Exception('创建失败');
        }
    }
}