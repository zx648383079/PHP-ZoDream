<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Message;
use Zodream\ThirdParty\WeChat\MessageResponse;

class MessageController extends Controller {

    public function indexAction($id) {
        $model = WeChatModel::find($id);
        $message = $model->sdk(Message::class);
        if ($message->isValid()) {
            // 准备接入
            $model->status = WeChatModel::STATUS_ACTIVE;
            $model->save();
            $message->valid();
        }
        return $message->on([EventEnum::ScanSubscribe, EventEnum::Subscribe],
            function(Message $message, MessageResponse $response) {
                $response->setText('谢谢关注！');
            })->on(EventEnum::Message, function(Message $message, MessageResponse $response) {
            $response->setText(sprintf('您的消息是: %s', $message->content));
        })->on(EventEnum::UnSubscribe, function(Message $message, MessageResponse $response) {
            $response->setText('取消关注');
        })->on(EventEnum::Click, function(Message $message, MessageResponse $response) {
            $response->setText(sprintf('您点击了 %s', $message->eventKey));
        })->run()->sendContent();
    }


    public function throwErrorMethod($action) {
        return $this->indexAction($action);
    }
}