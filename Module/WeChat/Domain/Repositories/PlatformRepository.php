<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Adapters\IPlatformAdapter;
use Module\WeChat\Domain\Adapters\IReplyAdapter;
use Module\WeChat\Domain\Adapters\WxAdapter;
use Module\WeChat\Domain\Model\WeChatModel;

class PlatformRepository {

    const PLATFORM_TYPE_WX = 0;

    public static function entry(int $id, bool $checkStatus = false): IReplyAdapter|IPlatformAdapter {
        $model = WeChatModel::findOrThrow($id);
        if ($checkStatus && $model->status !== WeChatModel::STATUS_ACTIVE &&
                $model->status !== WeChatModel::STATUS_INACTIVE) {
            throw new \Exception('error platform');
        }
        switch ($model->platform_type) {
            case self::PLATFORM_TYPE_WX:
                return new WxAdapter($model);
        };
        throw new \Exception('error platform');
    }
}