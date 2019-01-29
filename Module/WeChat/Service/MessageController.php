<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Module;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Message;
use Zodream\ThirdParty\WeChat\MessageResponse;

class MessageController extends Controller {

    /**
     * @var WeChatModel
     */
    protected $model;

    public function indexAction($id) {
        Module::reply()->setModel($this->model = WeChatModel::find($id));
        $message = $this->model->sdk(Message::class);
        if ($message->isValid()) {
            // 准备接入
            $this->model->status = WeChatModel::STATUS_ACTIVE;
            $this->model->save();
            $message->valid();
        }
        // 可以通过ip 和 $this->model->original === $message->getTo() 验证真实性
        return $this->bindEvent($message)->run();
    }

    /**
     * 绑定事件
     * @param Message $message
     * @return Message
     */
    protected function bindEvent(Message $message) {
        return $message->on([EventEnum::ScanSubscribe, EventEnum::Subscribe],
            function(Message $message, MessageResponse $response) {
                $this->subscribe($message->getFrom());
                Module::reply()->setMessage($message, $response)->replyEvent(EventEnum::Subscribe);
        })->on(EventEnum::Message,
            function(Message $message, MessageResponse $response)  {
                Module::reply()->setMessage($message, $response)->replyMessage($message->content);
        })->on(EventEnum::UnSubscribe,
            function(Message $message, MessageResponse $response)  {
                $this->unsubscribe($message->getFrom());
                Module::reply()->setMessage($message, $response)->replyEvent(EventEnum::UnSubscribe);
        })->on(EventEnum::Click,
            function(Message $message, MessageResponse $response) {
                if (!empty($message->eventKey) && strpos($message->eventKey, 'menu_') === 0) {
                    Module::reply()->setMessage($message, $response)->replyMenu(substr($message->eventKey, 5));
                }
        });
    }



    public function throwErrorMethod($action) {
        return $this->indexAction($action);
    }

    private function subscribe($openid) {
        $model = FansModel::where('openid', $openid)
            ->where('wid', $this->model->id)->first();
        if (empty($model)) {
            $model = new FansModel([
                'openid' => $openid,
                'wid' => $this->model->id
            ]);
        }
        $model->status = FansModel::STATUS_SUBSCRIBED;
        $model->save() && $model->updateUser(true);
    }

    private function unsubscribe($openid) {
        FansModel::where('openid', $openid)
            ->where('wid', $this->model->id)->update([
                'status' => FansModel::STATUS_UNSUBSCRIBED,
                'updated_at' => time()
            ]);
    }
}