<?php
namespace Service\Home;

use Domain\Model\Blog\PostModel;
use Domain\Model\FeedbackModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\Http\Requestquest;

class HomeController extends Controller {
    function indexAction() {

        $hots = PostModel::findAll([
            'limit' => 4,
            'order' => 'comment_count desc'
        ], 'id, title, description, create_at, comment_count');
        $news = PostModel::findAll([
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