<?php
namespace Module\WeChat\Domain;

use Module\WeChat\Domain\Adapters\AdapterEvent;
use Module\WeChat\Domain\Adapters\EmulateAdapter;
use Module\WeChat\Domain\Adapters\IReplyAdapter;
use Module\WeChat\Domain\Events\MessageRequest;
use Module\WeChat\Domain\Model\EditorModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Repositories\FollowRepository;
use Module\WeChat\Domain\Repositories\ReplyRepository;
use Module\WeChat\Domain\Scene\SceneInterface;

class MessageReply {

    public function __construct(
        protected IReplyAdapter $adapter,
        /**
         * @var array{from:string,event:AdapterEvent,content: string,created_at:int}
         */
        protected array $message,
    ) {
    }

    /**
     * @return array{to:string,type:int,content:string}
     */
    public function reply(): array {
        $this->emitListener();
        $reply = $this->replyOnScene();
        if (!empty($reply)) {
            return $this->formatReply($reply);
        }
        if ($this->message['event'] === AdapterEvent::Message) {
            $reply = $this->replyMessage($this->message['content']);
        } elseif ($this->message['event'] === AdapterEvent::MenuClick) {
            if ($this->message['content'] && str_starts_with($this->message['content'], 'menu_')) {
                $reply = $this->replyMenu(intval(substr($this->message['content'], 5)));
            }
        } else {
            $reply = $this->replyEvent($this->message['event']);
        }
        return $this->formatReply($reply);
    }

    protected function emitListener() {
        if ($this->adapter instanceof EmulateAdapter) {
            return;
        }
        switch ($this->message['event']) {
            case AdapterEvent::Subscribe:
                FollowRepository::add($this->adapter->platformId(),
                    $this->message['from'], $this->adapter->pullUser($this->message['from']));
                break;
            case AdapterEvent::UnSubscribe:
                FollowRepository::delete($this->adapter->platformId(), $this->message['from']);
                break;
            default:
                break;
        }
        event(new MessageRequest($this->adapter->platformId(),
            $this->message['from'], $this->message['to'], 0, $this->message['content']));
    }

    protected function replyOnScene() {
        $scene = $this->getScene();
        if (empty($scene)) {
            return [];
        }
        $scene->messageProvider($this);
        return $scene($this->message['content']);
    }

    /**
     * 获取消息回复
     * @param string $content
     * @return EditorModel|null
     */
    protected function replyMessage(string $content): ?EditorModel {
        return ReplyRepository::findWithCache($this->adapter->platformId(), $content);
    }

    /***
     * 转化菜单回复
     * @param int $id
     * @return EditorModel|null
     * @throws \Exception
     */
    protected function replyMenu(int $id): ?EditorModel {
        return MenuModel::find($id);
    }

    /**
     * 获取事件回复
     * @param AdapterEvent|string $event
     * @return EditorModel|null
     */
    protected function replyEvent(AdapterEvent|string $event): ?EditorModel {
        if ($event instanceof AdapterEvent) {
            $event = $event->getEventName();
        }
        return ReplyRepository::findWithEvent($event, $this->adapter->platformId());
    }

    /**
     * 转化响应
     * @param EditorModel|array|null $reply
     * @return array
     * @throws \Exception
     */
    protected function formatReply(null|EditorModel|array $reply): array {
        if (is_array($reply)) {
            return $reply;
        }
        $reply = $this->replyIfDefault($reply);
        if (is_array($reply)) {
            return $reply;
        }
        switch ($reply->type) {
            case EditorInput::TYPE_SCENE:
                $name = $reply->content;
                /** @var SceneInterface $instance */
                $instance = new $name();
                $instance->messageProvider($this);
                $res = $instance->enter($this->message['content']);
                break;
            default:
                return $reply->toArray();
        }
        if (empty($res)) {
            return $this->renderReply('');
        }
        return $this->formatReply($res);
    }

    /**
     * 转化回复
     * @param EditorModel|null $model
     * @return EditorModel|array
     */
    protected function replyIfDefault(?EditorModel $model) {
        if (!empty($model)) {
            return $model;
        }
        $model = ReplyRepository::findWithEvent(ReplyRepository::EVENT_DEFAULT, $this->adapter->platformId());
        return empty($model) ? $this->renderReply('默认回复') : $model;
    }

    public function renderReply(string $content, int $type = EditorInput::TYPE_TEXT): array {
        return compact('content', 'type');
    }

    public function renderData(int $type, array $data): array {
        $data['type'] = $type;
        return $data;
    }

    public function authUser() {
        return $this->adapter->authUser($this->message['from']);
    }

    public function authUserId(): int {
        return $this->adapter->authUserId($this->message['from']);
    }

    public function fromId(): string {
        return $this->message['from'];
    }

    public function oAuthType(): string {
        return $this->adapter->oAuthType();
    }

    public function id() {
        return sprintf('wx_scene_%s_%s',
            $this->adapter->platformId(),
            $this->message['from']);
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