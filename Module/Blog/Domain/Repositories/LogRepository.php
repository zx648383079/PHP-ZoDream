<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Module\Blog\Domain\Model\BlogLogModel;

final class LogRepository {
    /**
     * 切换记录
     * @param int $type
     * @param int $action
     * @param int $id
     * @param array|int|null $searchAction
     * @return int {0: 取消，1: 更新为，2：新增}
     * @throws \Exception
     */
    public static function toggleLog(int $type, int $action, int $id, array|int|null $searchAction = null): int {
        if (empty($searchAction)) {
            $searchAction = $action;
        }
        $log = BlogLogModel::where('user_id', auth()->id())
            ->where('item_type', $type)
            ->when(is_array($searchAction), function ($query) use ($searchAction) {
                $query->whereIn('action', $searchAction);
            }, function ($query) use ($searchAction) {
                $query->where('action', $searchAction);
            })
            ->where('item_id', $id)
            ->first();
        if (!empty($log) && $log->action === $action) {
            $log->delete();
            return 0;
        }
        if (!empty($log)) {
            $log->action = $action;
            $log->created_at = time();
            $log->save();
            return 1;
        }
        BlogLogModel::createOrThrow([
            'item_type' => $type,
            'item_id' => $id,
            'action' => $action,
            'user_id' => auth()->id()
        ]);
        return 2;
    }
}