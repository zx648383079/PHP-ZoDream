<?php
namespace Module\WeChat\Domain;

use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Scene\SceneInterface;
use Zodream\Infrastructure\Pipeline\InterruptibleProcessor;
use Zodream\Infrastructure\Pipeline\PipelineBuilder;
use Zodream\Infrastructure\Concerns\EventTrait;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Message;
use Zodream\ThirdParty\WeChat\MessageResponse;

class MessageReply {

    use EventTrait;

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

    private $user = -1;
    private $user_id = -1;

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
     * @param MessageResponse $response
     * @return MessageReply
     */
    public function setResponse(MessageResponse $response) {
        $this->response = $response;
        return $this;
    }

    /**
     * 回复
     * @return MessageResponse
     */
    public function reply() {
        $this->on([EventEnum::ScanSubscribe, EventEnum::Subscribe],
            function() {
                $this->replyEvent(EventEnum::Subscribe);
            })->on(EventEnum::Message,
            function()  {
                $this->replyMessage($this->message->content);
            })->on(EventEnum::UnSubscribe,
            function()  {
                $this->replyEvent(EventEnum::UnSubscribe);
            })->on(EventEnum::Click,
            function(Message $message) {
                if (!empty($message->eventKey)
                    && strpos($message->eventKey, 'menu_') === 0) {
                    $this->replyMenu(substr($message->eventKey, 5));
                }
            })->invoke($this->message->getEvent(), [$this->message, $this->response, $this]);
        return $this->response;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message {
        return $this->message;
    }

    /**
     * 获取openid
     * @return string
     * @throws \Exception
     */
    public function getOpenId() {
        return empty($this->message) ? session()->id() : $this->message->getFrom();
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getUserId(): int {
        if ($this->user_id === -1) {
            $this->user_id =
                empty($this->message) ? auth()->id() :
                OAuthModel::findUserId($this->getOpenId(), OAuthModel::TYPE_WX);
        }
        return $this->user_id;
    }

    /**
     * @return UserModel
     * @throws \Exception
     */
    public function getUser() {
        if ($this->user !== -1) {
            return $this->user;
        }
        $user_id = $this->getOpenId();
        if ($user_id < 1) {
            return null;
        }
        return UserModel::find($user_id);
    }

    /**
     * @return MessageResponse
     */
    public function getResponse(): MessageResponse {
        return $this->response;
    }

    /**
     * @return WeChatModel
     */
    public function getModel(): WeChatModel {
        return $this->model;
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
        $res = EditorInput::render($reply, $this->response);
        if (empty($res)) {
            return $this->response;
        }
        if ($res instanceof MessageResponse) {
            return $res;
        }
        return $this->inputResponse($res);
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
            'type' => EditorInput::TYPE_TEXT,
            'content' => '默认回复'
        ]) : $model;
    }


    public function id() {
        return sprintf('wx_scene_%s_%s', $this->model->id, $this->getOpenId());
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