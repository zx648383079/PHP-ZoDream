<?php
declare(strict_types=1);
namespace Module\Code\Domain\Repositories;

use Module\Code\Domain\Model\LogModel;

class LogRepository {

    public static function commentAgreeType(int $comment): int {
        if (auth()->guest()) {
            return 0;
        }
        $log = LogModel::where([
            'user_id' => auth()->id(),
            'item_type' => LogModel::TYPE_COMMENT,
            'item_id' => $comment,
        ])->whereIn('action', [LogModel::ACTION_AGREE, LogModel::ACTION_DISAGREE])->first('action');
        return !$log ? 0 : $log->action;
    }

    public static function isRecommend(int $micro): bool {
        if (auth()->guest()) {
            return false;
        }
        return LogModel::where([
                'user_id' => auth()->id(),
                'item_type' => LogModel::TYPE_CODE,
                'item_id' => $micro,
                'action' => LogModel::ACTION_RECOMMEND
            ])->count() > 0;
    }

    public static function isCollect(int $micro): bool {
        if (auth()->guest()) {
            return false;
        }
        return LogModel::where([
                'user_id' => auth()->id(),
                'item_type' => LogModel::TYPE_CODE,
                'item_id' => $micro,
                'action' => LogModel::ACTION_COLLECT
            ])->count() > 0;
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
    public static function toggleLog(int $type, int $action, int $id, array|int|null $searchAction = null): int {
        if (empty($searchAction)) {
            $searchAction = $action;
        }
        $log = LogModel::where('user_id', auth()->id())
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
        LogModel::createOrThrow([
            'item_type' => $type,
            'item_id' => $id,
            'action' => $action,
            'user_id' => auth()->id()
        ]);
        return 2;
    }
}
