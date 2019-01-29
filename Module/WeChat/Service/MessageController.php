<?php
namespace Module\WeChat\Service;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Scene\SceneInterface;
use Zodream\Database\DB;
use Zodream\Infrastructure\Pipeline\InterruptibleProcessor;
use Zodream\Infrastructure\Pipeline\PipelineBuilder;
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
        return $this->bindEvent($message)->run()->sendContent();
    }

    /**
     * 绑定事件
     * @param Message $message
     * @return Message
     */
    protected function bindEvent(Message $message) {
        return $message->on([EventEnum::ScanSubscribe, EventEnum::Subscribe], function(Message $message, MessageResponse $response) {
            $this->subscribe($message->getFrom());
            $this->parseResponse($this->getEventReply(EventEnum::Subscribe), $response, $message);
        })->on(EventEnum::Message, function(Message $message, MessageResponse $response) {
            $this->parseResponse($this->getMessageReply($message->content, $message->getFrom()),
                $response, $message);
        })->on(EventEnum::UnSubscribe, function(Message $message, MessageResponse $response) {
            $this->unsubscribe($message->getFrom());
            $this->parseResponse($this->getEventReply(EventEnum::UnSubscribe),
                $response, $message);
        })->on(EventEnum::Click, function(Message $message, MessageResponse $response) {
            if (!empty($message->eventKey) && strpos($message->eventKey, 'menu_') === 0) {
                $this->parseMenuResponse(substr($message->eventKey, 5), $response, $message);
            }
        });
    }

    /**
     * 获取消息回复
     * @param $content
     * @param $openid
     * @return ReplyModel
     */
    protected function getMessageReply($content, $openid) {
        // 先判断是否处在场景中
        $pipeline = (new PipelineBuilder())
            ->add(function ($content) use ($openid) {
                $scene = $this->getScene($openid);
                if (empty($scene)) {
                    return $content;
                }
                $model = $scene($content, $openid, $this->model);
                return $model instanceof ReplyModel ? $model : $content;
            })->add(function ($content) {
            return ReplyModel::findWithCache($this->model->id, $content);
        })->build(new InterruptibleProcessor(function ($payload) {
            return !$payload instanceof ReplyModel;
        }));
        return $pipeline($content);
        //
//        $model = ReplyModel::where('event', EventEnum::Message)
//            ->where('keywords', $content)
//            ->where('wid', $this->model->id)
//            ->orderBy('`match`', 'desc')->first();
//        if (!empty($model)) {
//            return $model;
//        }
//        $model_list = ReplyModel::where('event', EventEnum::Message)
//            ->where('wid', $this->model->id)
//            ->where('`match`', 0)->all();
//        foreach ($model_list as $item) {
//            if (strpos($content, $item->keywords) !== false) {
//                return $item;
//            }
//        }
//        return null;
    }

    /***
     * 转化菜单回复
     * @param $id
     * @param MessageResponse $response
     * @param Message $message
     * @return MessageResponse
     * @throws \Exception
     */
    protected function parseMenuResponse($id, MessageResponse $response, Message $message) {
        $model = MenuModel::find($id);
        if (empty($model)) {
            return $this->parseResponse(null, $response, $message);
        }
        return $this->parseResponse($model, $response, $message);
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

    /**
     * 转化响应
     * @param $reply
     * @param MessageResponse $response
     * @param Message $message
     * @return MessageResponse
     * @throws \Exception
     */
    protected function parseResponse($reply, MessageResponse $response, Message $message) {
        $reply = $this->parseReply($reply);
        $type = intval($reply->type);
        if ($type === ReplyModel::TYPE_TEXT) {
            return $response->setText($reply->content);
        }
        if ($type === ReplyModel::TYPE_URL) {
            return $response->setText($reply->content);
        }
        if ($type === ReplyModel::TYPE_SCENE) {
            $name = $reply->content;
            /** @var SceneInterface $instance */
            $instance = new $name;
            return $this->parseResponse($instance->enter($message->getFrom(),
                $this->model), $response, $message);
        }
    }

    /**
     * 转化回复
     * @param $model
     * @return ReplyModel|MenuModel
     */
    protected function parseReply($model) {
        if (!empty($model)) {
            return $model;
        }
        $model = $this->getEventReply('default');
        return empty($model) ? new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '默认回复'
        ]) : $model;
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

    /**
     * 获取当前的场景
     * @param $openid
     * @return SceneInterface
     * @throws \Exception
     */
    private function getScene($openid) {
        return cache()->get(sprintf('wx_scene_%s_%s', $this->model->id, $openid));
    }
}