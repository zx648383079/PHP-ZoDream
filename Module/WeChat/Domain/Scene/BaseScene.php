<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\MessageReply;
use Zodream\Infrastructure\Concerns\Attributes;

abstract class BaseScene implements SceneInterface {

    use Attributes;

    protected ?MessageReply $provider = null;

    public function messageProvider(MessageReply $message) {
        $this->provider = $message;
    }

    abstract public function process(string $content);

    public function leave() {
        if (!$this->provider) {
            return;
        }
        $this->provider->removeScene();
    }

    public function save() {
        if (!$this->provider) {
            return;
        }
        $this->provider->saveScene($this);
    }

    public function set($key, $value = null) {
        $this->setAttribute($key, $value);
        $this->save();
        return $this;
    }

    public function clear() {
        $this->clearAttribute();
        $this->save();
        return $this;
    }

    public function __invoke(string $content) {
        return $this->process($content);
    }

    public function __set($attribute, $value) {
        $this->set($attribute, $value);
    }

    public function __sleep(): array
    {
        return ['__attributes'];
    }

}