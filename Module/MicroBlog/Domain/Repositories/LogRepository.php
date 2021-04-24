<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Repositories;

use Module\MicroBlog\Domain\Model\LogModel;

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
            'item_type' => LogModel::TYPE_MICRO_BLOG,
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
                'item_type' => LogModel::TYPE_MICRO_BLOG,
                'item_id' => $micro,
                'action' => LogModel::ACTION_COLLECT
            ])->count() > 0;
    }
}
