<?php
namespace Service\Home;

use Zodream\Domain\Filter\Filters\PhoneFilter;
use Zodream\Domain\SMS\Ihuyi;
use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
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
    
    function smsAction($mobile) {
        if (empty($mobile) || !(new PhoneFilter())->validate($mobile)) {
            $this->ajaxReturn(array(
                'status' => 'failure',
                'error' => '手机号不正确！'
            ));
        }
        $code = StringExpand::randomNumber(6);
        $sms = new Ihuyi();
        $result = $sms->send($mobile, $code);
        if ($result === false) {
            $this->ajaxReturn(array(
                'status' => 'failure',
                'error' => $sms->getError()
            ));
        }
        $this->ajaxReturn(array(
            'status' => 'success',
            'code' => $code
        ));
    }
}