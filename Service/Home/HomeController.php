<?php
namespace Service\Home;

use Domain\Model\Blog\BlogModel;
use Domain\Model\FeedbackModel;

class HomeController extends Controller {
    public function indexAction() {
        $hots = BlogModel::findAll([
            'limit' => 4,
            'order' => 'comment_count desc'
        ], 'id, title, description, create_at, comment_count');
        $news = BlogModel::findAll([
            'limit' => 6,
            'order' => 'create_at desc'
        ], 'id, title, create_at');
        return $this->show('index', array(
            'title' => '首页',
            'hots' => $hots,
            'news' => $news,
            'banners' => []
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
            return $this->ajax([
                'code' => 0,
                'msg' => '感谢您的反馈！'
            ]);
        }
        return $this->ajax([
            'code' => 1,
            'msg' => '验证失败，请重试',
            'errors' => $model->getError()
        ]);
    }
}