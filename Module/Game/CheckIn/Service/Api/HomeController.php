<?php
namespace Module\Game\CheckIn\Service\Api;

use Module\Game\CheckIn\Domain\Model\CheckInModel;

class HomeController extends Controller {

    public function rules() {
        return [
            'index' => '@',
        ];
    }

    public function indexAction() {
        $model = CheckInModel::today()->where('user_id', auth()->id())->first();
        return $this->renderData($model);
    }

    public function checkInAction() {
        $model = CheckInModel::checkIn(auth()->id(), CheckInModel::METHOD_APP);
        if ($model) {
            return $this->renderData($model);
        }
        return $this->renderFailure('签到失败');
    }

    public function monthAction($month = null) {
        $timestamp = empty($month) ? time() : strtotime($month);
        $data = CheckInModel::month($timestamp)->where('user_id', auth()->id())->get();
        return $this->renderData($data);
    }

}