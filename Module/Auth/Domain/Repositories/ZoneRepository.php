<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Entities\ZoneEntity;
use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Model\UserMetaModel;

final class ZoneRepository {

    public static function userZone(): array {
        $lastAt = auth()->guest() ? 0 : intval(UserMetaModel::where('user_id', auth()->id())
            ->where('name', 'zone_at')->value('content'));
        $data = ZoneEntity::query()->where('status', 1)->orderBy('id', 'asc')->get();
        $activated_at = 0;
        $selected = [];
        if ($lastAt > 0) {
            $zoneId = intval(UserMetaModel::query()->where('user_id', auth()->id())
                ->where('name', 'zone_id')->value('content'));

            $selected = array_filter($data, function ($item) use ($zoneId) {
                return $item->id == $zoneId;
            });
            $activated_at = $lastAt +  30 * 86400;
            if ($activated_at <= time()) {
                $activated_at = 0;
            }
        }
        return compact('data', 'activated_at', 'selected');
    }

    public static function all(): array {
        return ZoneEntity::query()->where('status', 1)->orderBy('id', 'asc')->get();
    }

    public static function save(int $user, array|int $zoneId) {
        if (is_array($zoneId)) {
            $zoneId = intval(current($zoneId));
        }
        self::userChange($user, $zoneId, true);
    }

    public static function userChange(int $user, int $zoneId, bool $checkTime = true)
    {
        $lastAt = UserMetaModel::where('user_id', $user)
            ->where('name', 'zone_at')->first();
        if ($checkTime && !$lastAt && intval($lastAt->content) > time() - 30 * 86400) {
            throw new \Exception('时间间隔不允许设置');
        }
        if (!$lastAt) {
            UserMetaModel::query()->insert([
                [
                    'user_id' => $user,
                    'name' => 'zone_at',
                    'content' => time()
                ],
                [
                    'user_id' => $user,
                    'name' => 'zone_id',
                    'content' => $zoneId
                ]
            ]);
            return;
        }
        if ($checkTime) {
            UserMetaModel::query()->where('user_id', $user)
                ->where('name', 'zone_at')->update([
                    'content' => time()
                ]);
        }
        UserMetaModel::query()->where('user_id', $user)
            ->where('name', 'zone_id')->update([
                'content' => $zoneId
            ]);
    }

    public static function manageRemove(int $id) {
        ZoneEntity::query()->where('id', $id)->delete();
    }

    public static function manageList(string $keywords) {
        return ZoneEntity::query()
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, 'name', false, '', $keywords);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function manageSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? ZoneEntity::findOrThrow($id) : new ZoneEntity();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function getId(int $user): int {
        return intval(UserMetaModel::query()->where('user_id', $user)
            ->where('name', 'zone_id')->value('content'));
    }

    public static function getIdOrThrow(int $user): int {
        $zone = self::getId($user);
        if ($zone > 0) {
            return $zone;
        }
        throw AuthException::invalidZone();
    }


}