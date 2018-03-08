<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\MessageHistoryModel;

class LogController extends Controller {

    protected function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction() {
        $log_list = MessageHistoryModel::where('wid', $this->weChatId())
            ->page();
        return $this->show(compact('log_list'));
    }
}