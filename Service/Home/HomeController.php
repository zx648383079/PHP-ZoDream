<?php
namespace Service\Home;

use Domain\Model\FeedbackModel;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show('index', array(
            'title' => '首页'
        ));
    }

    public function aboutAction() {
        return $this->show('about', array(
            'title' => '关于'
        ));
    }

    public function feedbackAction() {
        $model = new FeedbackModel();
        if ($model->load() && $model->save()) {
            return $this->json([
                'code' => 0,
                'msg' => '感谢您的反馈！'
            ]);
        }
        return $this->json([
            'code' => 1,
            'msg' => '验证失败，请重试',
            'errors' => $model->getError()
        ]);
    }
}