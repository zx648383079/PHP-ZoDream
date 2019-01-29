<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\Model\WeChatModel;

abstract class BaseScene implements SceneInterface {

    public function enter($openid, WeChatModel $model) {
        // TODO: Implement enter() method.
    }

    abstract public function process($content);

    public function leave() {

    }

    public function __invoke($content, $openid, WeChatModel $model) {
        return $this->process($content);
    }

}