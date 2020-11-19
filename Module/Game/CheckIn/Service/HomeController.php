<?php
namespace Module\Game\CheckIn\Service;

use Module\Game\CheckIn\Domain\Model\CheckInModel;

class HomeController extends Controller {

    public function indexAction() {
        $model = CheckInModel::today()->where('user_id', auth()->id())->first();
        return $this->show(compact('model'));
    }

    public function checkInAction() {
        $model = CheckInModel::checkIn(auth()->id(), CheckInModel::METHOD_APP);
        if ($model) {
            return $this->renderData($model);
        }
        return $this->renderFailure('签到失败');
    }
}