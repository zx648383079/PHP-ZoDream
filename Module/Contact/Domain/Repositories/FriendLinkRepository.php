<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Domain\Constants;
use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Auth\Domain\Events\ManageAction;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Weights\FriendLink;
use Module\SEO\Domain\Repositories\SEORepository;

class FriendLinkRepository {
    public static function getList(string $keywords = '') {
        return FriendLinkModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'email', 'url', 'brief']);
        })
            ->orderBy([
                'status' => 'asc',
                'id' => 'desc'
            ])->page();
    }

    public static function get(int $id) {
        return FriendLinkModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = FriendLinkModel::findOrNew($id);
        $model->load($data);
        if ($id < 1) {
            $model->user_id = auth()->id();
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function toggle(int $id, string $remark) {
        $model = static::get($id);
        $model->status = $model->status > 0 ? 0  : 1;
        $model->save();
        event(new ManageAction('friend_link',
            sprintf('友情链接[%d]:%s，理由: %s', $model->url,
                $model->status > 0 ? '上架' : '下架', $remark)
            , Constants::TYPE_SYSTEM_FRIEND_LINK, $id));
        SEORepository::clearCache(['pages', 'nodes']);
        return $model;
    }

    public static function remove(array|int $id) {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        FriendLinkModel::whereIn('id', $items)->delete();
        cache()->delete(FriendLink::KEY);
    }
}