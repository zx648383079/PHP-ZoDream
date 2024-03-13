<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Module\Bot\Domain\Adapters\IPlatformAdapter;
use Module\Bot\Domain\Adapters\IReplyAdapter;
use Module\Bot\Domain\Adapters\Telegram\TelegramAdapter;
use Module\Bot\Domain\Adapters\WeChat\WxAdapter;
use Module\Bot\Domain\Model\BotModel;

class BotRepository {

    const PLATFORM_TYPE_WX = 0;
    const PLATFORM_TYPE_TELEGRAM = 0;

    public static function entry(int $id, bool $checkStatus = false): IReplyAdapter|IPlatformAdapter {
        $model = BotModel::findOrThrow($id);
        if ($checkStatus && $model->status !== BotModel::STATUS_ACTIVE &&
                $model->status !== BotModel::STATUS_INACTIVE) {
            throw new \Exception('error platform');
        }
        switch ($model->platform_type) {
            case self::PLATFORM_TYPE_WX:
                return new WxAdapter($model);
            case self::PLATFORM_TYPE_TELEGRAM:
                return new TelegramAdapter($model);
        };
        throw new \Exception('error platform');
    }
}