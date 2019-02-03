<?php
namespace Module\WeChat\Domain\Scene;

use Module\Auth\Domain\Model\Game\CheckInModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Module;

/**
 * 每日签到
 * @package Module\WeChat\Domain\Scene
 */
class CheckInScene extends BaseScene implements SceneInterface {

    public function enter() {
        $user_id = Module::reply()->getUserId();
        $model = CheckInModel::checkIn($user_id, CheckInModel::METHOD_WX);
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => empty($model) ? '签到失败' : sprintf('签到成功，已连续签到%s天', $model->running)
        ]);
    }

    public function process($content) {
        return;
    }
}