<?php
declare(strict_types=1);
namespace Domain\Repositories;

use Zodream\Database\Contracts\SqlBuilder;

abstract class ActionRepository {

    abstract protected static function query(): SqlBuilder;

    /**
     * 获取操作的总记录
     * @param int $item_id
     * @param int $item_type
     * @param int $action
     * @return int
     */
    public static function actionCount(int $item_id, int $item_type, int $action): int {
        return static::query()->where('item_id', $item_id)
            ->where('item_type', $item_type)
            ->where('action', $action)->count();
    }

    /**
     * 当前用户是否执行操作
     * @param int $item_id
     * @param int $item_type
     * @param int $action
     * @return bool
     * @throws \Exception
     */
    public static function userAction(int $item_id, int $item_type, int $action): bool {
        if (auth()->guest()) {
            return false;
        }
        return static::query()->where('item_id', $item_id)
            ->where('item_type', $item_type)
            ->where('user_id', auth()->id())
            ->where('action', $action)->count() > 0;
    }

    /**
     * 判断当前用户执行了那一个操作
     * @param int $item_id
     * @param int $item_type
     * @param array $onlyAction
     * @return int|null
     * @throws \Exception
     */
    public static function userActionValue(int $item_id, int $item_type, array $onlyAction): int|null {
        if (auth()->guest()) {
            return null;
        }
        $log = static::query()->where('user_id', auth()->id())
            ->where('item_type', $item_type)->where('item_id', $item_id)
            ->when(!empty($onlyAction), function ($query) use ($onlyAction) {
                if (count($onlyAction) === 1) {
                    $query->where('action', current($onlyAction));
                    return;
                }
                $query->whereIn('action', $onlyAction);
            })
            ->first('action');
        return !empty($log) ? intval($log['action']) : null;
    }

    /**
     * 取消或执行某个操作
     * @param int $item_id
     * @param int $item_type
     * @param int $action
     * @return bool
     * @throws \Exception
     */
    public static function toggleAction(int $item_id, int $item_type, int $action): bool {
        return static::toggleLog($item_type, $action, $item_id) > 0;
    }

    /**
     * 切换记录
     * @param int $type
     * @param int $action
     * @param int $id
     * @param array|int|null $searchAction
     * @return int {0: 取消，1: 更新为，2：新增}
     * @throws \Exception
     */
    public static function toggleLog(int $type, int $action,
                              int $id,
                              array|int|null $searchAction = null): int {
        if (auth()->guest()) {
            return 0;
        }
        if (empty($searchAction)) {
            $searchAction = $action;
        }
        $userId = auth()->id();
        $log = static::query()->where('user_id', $userId)
            ->where('item_type', $type)
            ->when(is_array($searchAction), function ($query) use ($searchAction) {
                if (count($searchAction) === 1) {
                    $query->where('action', current($searchAction));
                    return;
                }
                $query->whereIn('action', $searchAction);
            }, function ($query) use ($searchAction) {
                $query->where('action', $searchAction);
            })
            ->where('item_id', $id)
            ->first();
        if (!empty($log) && intval($log['action']) === $action) {
            static::query()->where('id', $log['id'])->delete();
            return 0;
        }
        if (!empty($log)) {
            static::query()->where('id', $log['id'])->update([
                    'action' => $action,
                    'created_at' => time()
                ]);
            return 1;
        }
        static::query()->insert([
            'item_id' => $id,
            'item_type' => $type,
            'user_id' => $userId,
            'action' => $action,
            'created_at' => time()
        ]);
        return 2;
    }


}