<?php
namespace Module\WeChat\Service;

use Module\ModuleController;
use Zodream\Infrastructure\Http\Response;
use Zodream\ThirdParty\WeChat\Platform\Manage;
use Zodream\ThirdParty\WeChat\Platform\Notify;

/**
 * 公众号第三方平台
 * @package Module\Wechat\Service
 */
class PlatformController extends ModuleController {
    /**
     * 接受平台通知
     */
    public function indexAction() {
        $notify = new Notify();
        return $notify->on(Notify::TYPE_Authorized, function (Notify $notify) {

        })->on(Notify::TYPE_UpdateAuthorized, function (Notify $notify) {

        })->run();
    }

    /**
     * 公众号授权
     * @return Response
     * @throws \Exception
     */
    public function loginAction() {
        $manage = new Manage();
        $uri = $manage->set('redirect_uri',
            url('wechat/callback', true))->login();
        return $this->redirect($uri);
    }

    /**
     *
     */
    public function callbackAction() {
        $manage = new Manage();
        $args = $manage->callback();
    }

    /**
     * 公众号事件推送
     * @param $openid
     */
    public function messageAction($openid) {
        dd($openid);
        /*$message = WeChat::instance()->message();
        $response = $message->on(EventEnum::Message, function(Message $message, MessageResponse $response) {

        })->run();
        echo 'success';*/
    }
}