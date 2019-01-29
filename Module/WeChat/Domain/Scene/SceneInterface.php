<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;

interface SceneInterface {

    /**
     * @param $openid
     * @param WeChatModel $model
     * @return ReplyModel
     */
    public function enter($openid, WeChatModel $model);

    public function leave();

    public function __invoke($content, $openid, WeChatModel $model);
}