<?php
declare(strict_types=1);
namespace Module\Chat\Domain\Listeners;

use Module\Chat\Domain\Model\ChatHistoryModel;
use Module\Chat\Domain\Model\MessageModel;
use Module\Team\Domain\Events\DisbandTeam;

class DisbandTeamListener {

    public function __construct(DisbandTeam $event) {
        MessageModel::where('group_id', $event->teamId)->delete();
        ChatHistoryModel::where('item_id', $event->teamId)
            ->where('item_type', 1)->delete();
    }
}
