<?php
namespace Module\WeChat\Service;

use Module\ModuleController;
use Module\WeChat\Domain\Model\MessageHistoryModel;

class LogController extends ModuleController {
    public function indexAction() {
        $log_list = MessageHistoryModel::page();
        return $this->show(compact('log_list'));
    }
}