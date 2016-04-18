<?php
namespace Service\Home;


use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\Request;

class AdminController extends Controller {
    function indexAction() {
        $this->show(array(
        ));
    }

    /**
     * @param Post $post
     */
    function indexPost($post) {
        Log::save($post->get(), 'honeypot');
        $this->send('message', '账户不存在或密码错误！');
    }
}