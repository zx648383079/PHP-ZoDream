<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Module;

abstract class BaseScene implements SceneInterface {

    abstract public function process($content);

    public function leave() {
        Module::reply()->removeScene();
    }

    public function save() {
        Module::reply()->saveScene($this);
    }

    public function __invoke($content) {
        return $this->process($content);
    }

}