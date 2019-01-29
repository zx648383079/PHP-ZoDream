<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;

/**
 * 每日签到
 * @package Module\WeChat\Domain\Scene
 */
class CheckInScene extends BaseScene implements SceneInterface {

    public function enter() {
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '签到成功，已签到1天'
        ]);
    }

    public function process($content) {
        return;
    }
}