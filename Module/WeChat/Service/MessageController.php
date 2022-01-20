<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\Events\MessageRequest;
use Module\WeChat\Domain\MessageReply;
use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Repositories\WxRepository;
use Module\WeChat\Module;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Message;

class MessageController extends Controller {

    /**
     * @var WeChatModel
     */
    protected $model;

    public function indexAction(Output $output, int $id) {
        $this->model = WeChatModel::find($id);
        if (empty($this->model) || $this->model->status < 0) {
            return $output->statusCode(400);
        }
        $reply = Module::reply()->setModel($this->model);
        $message = $this->model->sdk(Message::class);
        if ($message->isValid()) {
            // 准备接入
            $this->model->status = WeChatModel::STATUS_ACTIVE;
            $this->model->save();
            $message->valid();
        }
        // 可以通过ip 和 $this->model->original === $message->getTo() 验证真实性
        if (!$message->verifyServer($this->model->original)) {
            //return $message->getResponse();
        }
        $reply->setMessage($message, $message->getResponse());
        return $this->bindEvent($reply)->reply()->ready($output);
    }

    /**
     * 绑定事件
     * @param MessageReply $reply
     * @return MessageReply
     */
    protected function bindEvent(MessageReply $reply) {
        return $reply->on([EventEnum::ScanSubscribe, EventEnum::Subscribe],
            function(Message $message) {
                WxRepository::subscribe($this->model, $message->getFrom());
        })->on(EventEnum::UnSubscribe,
            function(Message $message)  {
                WxRepository::unsubscribe($this->model->id, $message->getFrom());
        })->on(EventEnum::Message, function (Message $message) {
            event(new MessageRequest($this->model->id,
                $message->getFrom(), $message->getTo(), 0, $message->content));
        })->on(EventEnum::Click, function (Message $message) {
            event(new MessageRequest($this->model->id,
                $message->getFrom(), $message->getTo(), 1, $message->eventKey));
        });
    }



    public function throwErrorMethod($action) {
        return $this->indexAction($action);
    }
}