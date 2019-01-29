<?php
namespace Module\WeChat\Domain;

use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Scene\SceneInterface;
use Zodream\Infrastructure\Pipeline\InterruptibleProcessor;
use Zodream\Infrastructure\Pipeline\PipelineBuilder;
use Zodream\ThirdParty\WeChat\Message;
use Zodream\ThirdParty\WeChat\MessageResponse;

class MessageReply {

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var MessageResponse
     */
    protected $response;

    /**
     * @var WeChatModel
     */
    protected $model;

    public function setModel(WeChatModel $model) {
        $this->model = $model;
        return $this;
    }

    public function setMessage(Message $message, MessageResponse $response) {
        $this->message = $message;
        $this->response = $response;
        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message {
        return $this->message;
    }

    /**
     * 获取消息回复
     * @param $content
     * @return MessageReply
     * @throws \Exception
     */
    public function replyMessage($content) {
        // 先判断是否处在场景中
        $pipeline = (new PipelineBuilder())
            ->add(function ($content) {
                $scene = $this->getScene();
                if (empty($scene)) {
                    return $content;
                }
                $model = $scene($content);
                return $model instanceof ReplyModel ? $model : $content;
            })->add(function ($content) {
                return ReplyModel::findWithCache($this->model->id, $content);
            })->build(new InterruptibleProcessor(function ($payload) {
                return !$payload instanceof ReplyModel;
            }));
        $this->inputResponse($pipeline($content));
        return $this;
    }

    /***
     * 转化菜单回复
     * @param $id
     * @return MessageResponse
     * @throws \Exception
     */
    public function replyMenu($id) {
        $model = MenuModel::find($id);
        if (empty($model)) {
            return $this->inputResponse(null);
        }
        return $this->inputResponse($model);
    }

    /**
     * 获取事件回复
     * @param string $event
     * @return MessageReply
     * @throws \Exception
     */
    public function replyEvent($event) {
        $model = ReplyModel::findWithEvent($event, $this->model->id);
        $this->inputResponse($model);
        return $this;
    }

    /**
     * 转化响应
     * @param $reply
     * @return MessageResponse
     * @throws \Exception
     */
    protected function inputResponse($reply) {
        $reply = $this->replyIfDefault($reply);
        $type = intval($reply->type);
        if ($type === ReplyModel::TYPE_TEXT) {
            return $this->response->setText($reply->content);
        }
        if ($type === ReplyModel::TYPE_URL) {
            return $this->response->setText($reply->content);
        }
        if ($type === ReplyModel::TYPE_SCENE) {
            $name = $reply->content;
            /** @var SceneInterface $instance */
            $instance = new $name();
            return $this->inputResponse($instance->enter());
        }
    }

    /**
     * 转化回复
     * @param $model
     * @return ReplyModel|MenuModel
     */
    protected function replyIfDefault($model) {
        if (!empty($model)) {
            return $model;
        }
        $model = ReplyModel::findWithEvent('default', $this->model->id);
        return empty($model) ? new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '默认回复'
        ]) : $model;
    }


    public function id() {
        return sprintf('wx_scene_%s_%s', $this->model->id, $this->message->getFrom());
    }

    /**
     * 获取当前的场景
     * @return SceneInterface
     * @throws \Exception
     */
    public function getScene() {
        return cache()->get($this->id());
    }

    public function saveScene(SceneInterface $scene) {
        cache()->set($this->id(), $scene, 3600);
    }

    public function removeScene() {
        cache()->delete($this->id());
    }
}