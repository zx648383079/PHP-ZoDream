<?php
namespace App\Controller;

use App\Lib\WeChat\Message;
use App\Model\WechatModel;

class WechatController extends Controller
{
	function indexAction()
	{
		//Message::valid();
		Message::text('sdasdas哈');
		if (isset(Message::get()->MsgType)) 
		{
			switch (Message::get()->MsgType) {
				case 'event':
					$this->_event();
					break;
				case 'text':
					$this->_text();
					break;
				default:
					;
				break;
			}
			
			$content .= Wechat::getMsg()->Content;
		}
		
		$wechat = new WechatModel();
		$wechat->add(WeChat::getAppMsg());
		
		Wechat::textMsg($content);
		//writeLog($wechat->msg);
		exit;
	}
	
	private function _text() {
		
	}
	
	private function _event() {
		switch (Message::get()->Event) {
			case 'subscribe':
				;
				break;
			case 'unsubscribe':
				;
				break;
			default:
				;
			break;
		}
	}
} 