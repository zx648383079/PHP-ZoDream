<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Scene;

use Module\Bot\Domain\MessageReply;
use Module\Bot\Domain\Model\ReplyModel;

interface SceneInterface {

    public function messageProvider(MessageReply $message);
    /**
     * @return ReplyModel
     */
    public function enter(string $content);

    public function leave();

    public function __invoke(string $content);
}