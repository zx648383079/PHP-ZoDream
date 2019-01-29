<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;

/**
 * 绑定会员
 * @package Module\WeChat\Domain\Scene
 */
class BindingScene extends BaseScene implements SceneInterface {

    public function enter($openid, WeChatModel $model) {
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '请输入账号'
        ]);
    }

    public function process($content) {
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '请输入密码'
        ]);
    }
}