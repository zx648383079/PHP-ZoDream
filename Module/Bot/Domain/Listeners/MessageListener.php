<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Listeners;

use Module\Bot\Domain\Events\MessageRequest;
use Module\Bot\Domain\Model\MessageHistoryModel;

final class MessageListener {

    public function __construct(MessageRequest $source) {
        MessageHistoryModel::create([
            'bot_id' => $source->bot_id,
            'type' => MessageHistoryModel::TYPE_REQUEST,
            'from' => $source->from,
            'to' => $source->to,
            'created_at' => $source->timestamp,
            'item_type' => $source->type,
            'item_id' => $source->type === 1 && str_starts_with($source->content, 'menu_') ?
                intval(substr($source->content, 5)) : 0,
            'content' => $source->content,
        ]);
    }
}
