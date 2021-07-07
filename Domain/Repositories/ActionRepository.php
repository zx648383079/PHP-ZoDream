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
     * @param array $action
     * @return int
     * @throws \Exception
     */
    public static function userActionValue(int $item_id, int $item_type, array $action): int {
        if (auth()->guest()) {
            return -1;
        }
        $log = static::query()->where('item_id', $item_id)
            ->where('item_type', $item_type)
            ->where('user_id', auth()->id())
            ->whereIn('action', $action)->first('action');
        return empty($log) ? -1 : intval($log['action']);
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
        if (auth()->guest()) {
            return false;
        }
        $userId = auth()->id();
        if (static::userAction($item_id, $item_type, $action)) {
            static::query()->where('item_id', $item_id)
                ->where('item_type', $item_type)
                ->where('user_id', $userId)
                ->where('action', $action)->delete();
            return false;
        }
        static::query()->insert([
            'item_id' => $item_id,
            'item_type' => $item_type,
            'user_id' => $userId,
            'action' => $action,
            'created_at' => time()
        ]);
        return true;
    }

    /**
     * 在一组操作中切换到某个操作
     * @param int $item_id
     * @param int $item_type
     * @param int $action
     * @param array $actionMap
     * @param int $oldAction
     * @return int
     * @throws \Exception
     */
    public static function changeAction(int $item_id, int $item_type, int $action, array $actionMap, int &$oldAction = -1): int {
        if (auth()->guest()) {
            return -1;
        }
        $userId = auth()->id();
        $oldAction = static::userActionValue($item_id, $item_type, $actionMap);
        if ($oldAction < 0) {
            static::query()->insert([
                'item_id' => $item_id,
                'item_type' => $item_type,
                'user_id' => $userId,
                'action' => $action,
                'created_at' => time()
            ]);
            return $action;
        }
        if ($oldAction === $action) {
            static::query()->where('item_id', $item_id)
                ->where('item_type', $item_type)
                ->where('user_id', $userId)
                ->whereIn('action', $actionMap)->delete();
            return -1;
        }
        static::query()->where('item_id', $item_id)
            ->where('item_type', $item_type)
            ->where('user_id', $userId)
            ->whereIn('action', $actionMap)->update([
                'action' => $action,
                'created_at' => time()
            ]);
        return $action;
    }
}