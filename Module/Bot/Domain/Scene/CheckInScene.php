<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Scene;

use Module\Game\CheckIn\Domain\Model\CheckInModel;

/**
 * 每日签到
 * @package Module\Bot\Domain\Scene
 */
class CheckInScene extends BaseScene implements SceneInterface {

    public function enter(string $content) {
        $user_id = $this->provider->authUserId();
        if ($user_id < 1) {
            return $this->provider->renderReply('请先绑定账户');
        }
        $model = CheckInModel::checkIn($user_id, CheckInModel::METHOD_WX);
        return $this->provider->renderReply(empty($model) ? '今日已签到' : sprintf('签到成功，已连续签到%s天', $model->running));
    }

    public function process(string $content) {
        return;
    }
}