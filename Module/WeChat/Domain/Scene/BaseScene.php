<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Module;
use Zodream\Infrastructure\Traits\Attributes;

abstract class BaseScene implements SceneInterface {

    use Attributes;

    abstract public function process($content);

    public function leave() {
        Module::reply()->removeScene();
    }

    public function save() {
        Module::reply()->saveScene($this);
    }

    public function setAttribute($key, $value = null) {
        parent::setAttribute($key, $value);
        $this->save();
        return $this;
    }

    public function __invoke($content) {
        return $this->process($content);
    }

}