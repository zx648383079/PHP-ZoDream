<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Module;

/**
 * 查询用户信息
 * @package Module\WeChat\Domain\Scene
 */
class BalanceScene extends BaseScene implements SceneInterface {

    public function enter() {
        $user = Module::reply()->getUser();
        if (empty($user)) {
            return new ReplyModel([
                'type' => EditorInput::TYPE_TEXT,
                'content' => '请先绑定账户'
            ]);
        }
        return static::balance();
    }

    public function process($content) {
        return;
    }

    public static function balance() {
        return new ReplyModel([
            'type' => EditorInput::TYPE_TEXT,
            'content' => sprintf('您的账户余额为 %s', Module::reply()->getUser()->money)
        ]);
    }
}