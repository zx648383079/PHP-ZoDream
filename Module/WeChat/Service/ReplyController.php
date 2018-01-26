<?php
namespace Module\WeChat\Service;

use Module\ModuleController;

class ReplyController extends ModuleController {
    public function indexAction() {
        return $this->show();
    }
}