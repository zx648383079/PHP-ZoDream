<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\Model\ReplyModel;

interface SceneInterface {

    /**
     * @return ReplyModel
     */
    public function enter();

    public function leave();

    public function __invoke($content);
}