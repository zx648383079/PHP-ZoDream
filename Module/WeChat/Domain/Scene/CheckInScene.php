<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Scene;

use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Module;

/**
 * 每日签到
 * @package Module\WeChat\Domain\Scene
 */
class CheckInScene extends BaseScene implements SceneInterface {

    public function enter() {
        $user_id = Module::reply()->getUserId();
        if ($user_id < 1) {
            return new ReplyModel([
                'type' => EditorInput::TYPE_TEXT,
                'content' => '请先绑定账户'
            ]);
        }
        $model = CheckInModel::checkIn($user_id, CheckInModel::METHOD_WX);
        return new ReplyModel([
            'type' => EditorInput::TYPE_TEXT,
            'content' => empty($model) ? '今日已签到' : sprintf('签到成功，已连续签到%s天', $model->running)
        ]);
    }

    public function process($content) {
        return;
    }
}