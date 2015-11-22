<?php
namespace App\Controller;

use App\Lib\WeChat\Message;
use App\Model\WechatModel;
use App\Model\MessageModel;
use App\Model\AccountModel;

class WechatController extends Controller {
	function indexAction() {
		//Message::valid();
		Message::text('hhh');
		die;
		if (isset(Message::get()->MsgType)) {
			$text = '不错！';
			switch (Message::get()->MsgType) {
				case 'event':
					$text = $this->_event();
					break;
				case 'text':
					$text = $this->_text();
					break;
				default:
					;
				break;
			}
			Message::text($text);
		}
		exit;
	}
	
	private function _text() {
		$model = new MessageModel();
		$data  = $model->findByKeyword(Wechat::getMsg()->Content);
		if (empty($data)) {
			$text = "您给的问题暂时无法回答！";
		} else {
			$text = $data['content'];
		}
		return $text;
	}
	
	private function _event() {
		$model = new AccountModel();
		switch (Message::get()->Event) {
			case 'subscribe':
				$model->addOpenID(Message::get()->FromUserName);
				return '这是关注，测试中！';
				break;
			case 'unsubscribe':
				$model->deleteByOpenID(Message::get()->FromUserName);
				return '您取消关注了！';
				break;
			default:
				;
			break;
		}
	}
} 