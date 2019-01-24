<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Message;
use Zodream\ThirdParty\WeChat\MessageResponse;

class MessageController extends Controller {

    /**
     * @var WeChatModel
     */
    protected $model;

    public function indexAction($id) {
        $this->model = WeChatModel::find($id);
        $message = $this->model->sdk(Message::class);
        if ($message->isValid()) {
            // 准备接入
            $this->model->status = WeChatModel::STATUS_ACTIVE;
            $this->model->save();
            $message->valid();
        }
        return $message->on([EventEnum::ScanSubscribe, EventEnum::Subscribe], function(Message $message, MessageResponse $response) {
                $this->subscribe($message->getFrom());
                $this->parseResponse($this->getEventReply(EventEnum::Subscribe, $response));
            })->on(EventEnum::Message, function(Message $message, MessageResponse $response) {
                $this->parseResponse($this->getMessageReply($message->content), $response);
            })->on(EventEnum::UnSubscribe, function(Message $message, MessageResponse $response) {
                $this->unsubscribe($message->getFrom());
                $this->parseResponse($this->getEventReply(EventEnum::UnSubscribe, $response));
            })->on(EventEnum::Click, function(Message $message, MessageResponse $response) {
                if (!empty($message->eventKey) && strpos($message->eventKey, 'menu_') === 0) {
                    $this->parseMenuResponse(substr($message->eventKey, 5), $response);
                }
            })->run()->sendContent();
    }

    /**
     * @param $content
     * @return ReplyModel
     */
    protected function getMessageReply($content) {
        $model = ReplyModel::where('event', EventEnum::Message)
            ->where('keywords', $content)
            ->where('wid', $this->model->id)
            ->orderBy('match', 'desc')->first();
        if (!empty($model)) {
            return $model;
        }
        $model_list = ReplyModel::where('event', EventEnum::Message)
            ->where('wid', $this->model->id)
            ->where('match', 0)->all();
        foreach ($model_list as $item) {
            if (strpos($content, $item->keywords) !== false) {
                return $item;
            }
        }
        return null;
    }

    protected function parseMenuResponse($id, MessageResponse $response) {
        $model = MenuModel::find($id);
        if (empty($model)) {
            return $this->parseResponse(null, $response);
        }
        return $this->parseResponse($model, $response);
    }

    /**
     * 获取事件回复
     * @param string $event
     * @return ReplyModel
     */
    protected function getEventReply($event) {
        return ReplyModel::where('event', $event)
            ->where('wid', $this->model->id)->first();
    }

    protected function parseResponse($reply, MessageResponse $response) {
        $reply = $this->parseReply($reply);
        if ($reply->type === ReplyModel::TYPE_TEXT) {
            return $response->setText($reply->content);
        }
        if ($reply->type === ReplyModel::TYPE_URL) {
            return $response->setText($reply->content);
        }
    }

    /**
     * @param $model
     * @return ReplyModel|MenuModel
     */
    protected function parseReply($model) {
        if (!empty($model)) {
            return $model;
        }
        return $this->getEventReply('default');
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