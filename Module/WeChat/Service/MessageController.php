<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\MessageReply;
use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Module;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Message;

class MessageController extends Controller {

    /**
     * @var WeChatModel
     */
    protected $model;

    public function indexAction(int $id) {
        $reply = Module::reply()->setModel($this->model = WeChatModel::find($id));
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
        return $this->bindEvent($reply)->reply();
    }

    /**
     * 绑定事件
     * @param MessageReply $reply
     * @return MessageReply
     */
    protected function bindEvent(MessageReply $reply) {
        return $reply->on([EventEnum::ScanSubscribe, EventEnum::Subscribe],
            function(Message $message) {
                $this->subscribe($message->getFrom());
        })->on(EventEnum::UnSubscribe,
            function(Message $message)  {
                $this->unsubscribe($message->getFrom());
        });
    }



    public function throwErrorMethod($action) {
        return $this->indexAction($action);
    }

    private function subscribe(string $openid) {
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

    private function unsubscribe(string $openid) {
        FansModel::where('openid', $openid)
            ->where('wid', $this->model->id)->update([
                'status' => FansModel::STATUS_UNSUBSCRIBED,
                'updated_at' => time()
            ]);
    }
}