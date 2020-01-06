<?php
namespace Module\Contact\Service;

use Module\Contact\Domain\Model\FeedbackModel;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Model\SubscribeModel;
use Module\ModuleController;

class HomeController extends ModuleController {

    protected function rules() {
        return [
            'unsubscribe' => '*',
            '*' => 'p'
        ];
    }

    public function indexAction() {

    }

    public function feedbackAction() {
        $model = new FeedbackModel();
        if (!$model->load() || !$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess(null, '提交留言成功');
    }

    public function subscribeAction() {
        $model = new SubscribeModel();
        if (!$model->load() || !$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess(null, '订阅成功');
    }

    public function unsubscribeAction($email) {
        SubscribeModel::where('email', $email)->update([
            'status' => 1,
            'updated_at' => time()
        ]);
    }

    public function friendLinkAction() {
        $model = new FriendLinkModel();
        if (!$model->load() || !$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess(null, '提交成功，请等待审核');
    }
}