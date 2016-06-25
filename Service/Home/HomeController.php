<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Infrastructure\HtmlExpand;
use Zodream\Domain\Authentication\Auth;
use Zodream\Infrastructure\Config;
use Zodream\Infrastructure\Request;

class HomeController extends Controller {
    function indexAction() {
        $this->runCache('home.index');
        $data = EmpireModel::query('post')->findAll([
            'limit' => 4,
            'order' => 'recommend desc'
        ]);
        $this->show(array(
            'title' => '首页',
            'data' => $data
        ));
    }

    function aboutAction() {
        $this->runCache('home.about');
        $this->show(array(
            'title' => '关于',
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