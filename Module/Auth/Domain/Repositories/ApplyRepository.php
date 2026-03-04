<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\ApplyLogModel;
use Domain\Model\SearchModel;
use Exception;

final class ApplyRepository {

    const int STATUS_NONE = 0;
    const int STATUS_CONFIRM = 1;
    const int STATUS_REJECT = 7;
    const int STATUS_DELETED = 9;

    public static function getList(string $keywords = '', int $type = 0, int $user = 0) {
        return ApplyLogModel::with('user')
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['remark']);
            })->where('type', $type)
            ->orderBy('created_at', 'desc')->page();
    }

    public static function change(int $id, int $status) {
        $model = ApplyLogModel::findOrThrow($id);
        if ($model->status == $status) {
            return $model;
        }
        $model->status = $status;
        $model->save();
        return $model;
    }


    public static function receiveCancel(int $user, int $itemType, int $itemId) {
        ApplyLogModel::where('user_id', $user)
                ->where('item_type', $itemType)
                ->where('status', self::STATUS_NONE)
                ->where('item_id', $itemId)->update([
                    'status' => self::STATUS_DELETED,
                    'updated_at' => time()
                ]);
    }

    public static function receiveClear(int $itemType, int $itemId) {
        ApplyLogModel::where('item_type', $itemType)
                ->where('item_id', $itemId)
                ->where('status', self::STATUS_NONE)
                ->update([
                    'status' => self::STATUS_DELETED,
                    'updated_at' => time()
                ]);
    }

    public static function receive(int $user, int $itemType, int $itemId, int $status) {
        ApplyLogModel::where('user_id', $user)
            ->where('item_type', $itemType)
            ->where('status', self::STATUS_NONE)
            ->where('item_id', $itemId)->update([
                'status' => $status,
                'updated_at' => time()
            ]);
    }

    public static function receiveCreate(int $user, int $itemType, int $itemId, string $remark) {
        ApplyLogModel::create([
            'item_type' => $itemType,
            'item_id' => $itemId,
            'remark' => $remark,
            'user_id' => $user,
            'status' => self::STATUS_NONE,
        ]);
    }

    public static function receiveSearch(int $itemType, int $itemId) {
        return ApplyLogModel::with('user')
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')->page();
    }

    public static function receiveUnread(int $itemType, int $itemId, int $lastAt = 0): int {
        return ApplyLogModel::where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('status', self::STATUS_NONE)
            ->when($lastAt > 0, function ($query) use ($lastAt) {
                $query->where('created_at', '>', $lastAt);
            })->count();
    }

    public static function receiveAny(int $user, int $itemType, int $itemId): bool {
        return ApplyLogModel::where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('user_id', $user)->count() > 0;
    }
}