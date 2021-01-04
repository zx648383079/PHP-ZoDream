<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\MessageHistoryModel;

class LogController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction($mark = false) {
        $log_list = MessageHistoryModel::where('wid', $this->weChatId())
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('mark', intval($mark));
            })
            ->page();
        return $this->show(compact('log_list'));
    }

    public function markAction($id) {
        MessageHistoryModel::where('id', $id)->updateBool('mark');
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function deleteAction($id) {
        MessageHistoryModel::where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }
}