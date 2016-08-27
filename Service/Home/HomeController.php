<?php
namespace Service\Home;

use Domain\Model\Blog\PostModel;
use Domain\Model\FeedbackModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Request;

class HomeController extends Controller {
    function indexAction() {
        $data = PostModel::findAll([
            'limit' => 4,
            'order' => 'recommend desc'
        ]);
        return $this->show('index', array(
            'title' => '首页',
            'data' => $data
        ));
    }

    function aboutAction() {
        $model = new FeedbackModel();
        if ($model->load() && $model->save()) {
            $model->clear();
        }
        return $this->show('about', array(
            'title' => '关于',
            'model' => $model
        ));
    }

    /**
     * @param Request\Post $post
     * 
     */
    function aboutPost($post) {
        $result = EmpireModel::query('feedback')->save([
            'name' => 'required|string:1-45',
            'email' => 'required|email',
            'phone' => 'phone',
            'content' => 'required',
            'ip' => '',
            'user_id' => '',
            'create_at' => ''
        ], $post->get());
        if (empty($result)) {
            $this->send('message', '验证失败，请重试');
            return;
        }
        $this->send('message', '提交成功！');
    }
}