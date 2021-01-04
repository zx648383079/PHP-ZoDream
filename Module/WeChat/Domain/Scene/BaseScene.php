<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Module;
use Zodream\Infrastructure\Concerns\Attributes;

abstract class BaseScene implements SceneInterface {

    use Attributes;

    abstract public function process($content);

    public function leave() {
        Module::reply()->removeScene();
    }

    public function save() {
        Module::reply()->saveScene($this);
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

    public function __invoke($content) {
        return $this->process($content);
    }

    public function __set($attribute, $value) {
        $this->set($attribute, $value);
    }

}