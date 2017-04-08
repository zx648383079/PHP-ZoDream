<?php
namespace Service\WeChat;

use Zodream\Domain\ThirdParty\WeChat\Message;
use Zodream\Domain\ThirdParty\WeChat\MessageEnum;
use Zodream\Domain\ThirdParty\WeChat\MessageResponse;
use Zodream\Service\Factory;

class HomeController extends Controller {
	public function indexAction() {
		$wechat = new Message();
        Factory::root()->childFile('data/log/'.time().'.log')
            ->write(file_get_contents('php://input'));
		return $wechat->on(DEFAULT_EVENT, function(Message $message, MessageResponse $response) {
            Factory::logger()->log(var_export($message->get(), true), 'wehcat');
            $response->setText('你好啊！');
        })->run()->sendContent();
	}
}