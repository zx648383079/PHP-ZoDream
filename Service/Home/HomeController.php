<?php
namespace Service\Home;

use Module\Template\Domain\Model\FeedbackModel;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function aboutAction() {
        if (app('request')->isPost()) {
            $model = new FeedbackModel();
            if ($model->load() && $model->save()) {

            }
        }
        return $this->show();
    }

    public function friendLinkAction() {
        if (app('request')->isPost() && app('request')->has('url')) {
            FeedbackModel::create([
                'name' =>  app('request')->get('name'),
                'email' =>  app('request')->get('url'),
                'content' =>  app('request')->get('brief'),
            ]);
        }
        return $this->show();
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