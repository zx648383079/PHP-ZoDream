<?php
namespace Module\Game\CheckIn\Service\Api;

use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    protected function rules() {
        return [
            'index' => '@',
        ];
    }

    public function indexAction() {
        $model = CheckInModel::today()->where('user_id', auth()->id())->first();
        return $this->render([
            'data' => $model
        ]);
    }

    public function checkInAction() {
        $model = CheckInModel::checkIn(auth()->id(), CheckInModel::METHOD_APP);
        if ($model) {
            return $this->render([
                'data' => $model
            ]);
        }
        return $this->renderFailure('签到失败');
    }

    public function monthAction($month = null) {
        $timestamp = empty($month) ? time() : strtotime($month);
        $data = CheckInModel::month($timestamp)->where('user_id', auth()->id())->get();
        return $this->render($data);
    }

}