<?php
namespace Module\Contact\Service\Api;

use Module\Contact\Domain\Model\FeedbackModel;
use Module\Contact\Domain\Model\SubscribeModel;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    public function feedbackAction() {
        $model = new FeedbackModel();
        if (!$model->load() || !$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->render(['data' => true]);
    }

    public function subscribeAction() {
        $model = new SubscribeModel();
        if (!$model->load() || !$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->render(['data' => true]);
    }

    public function unsubscribeAction($email) {
        SubscribeModel::where('email', $email)->update([
            'status' => 1,
            'updated_at' => time()
        ]);
        return $this->render(['data' => true]);
    }
}