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

    public function errorAction() {
        return $this->show('/404');
    }
}