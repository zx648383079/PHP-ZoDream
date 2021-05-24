<?php
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Model\MessageHistoryModel;

class LogController extends Controller {


    public function indexAction($mark = false) {
        $log_list = MessageHistoryModel::where('wid', $this->weChatId())
            ->when($mark !== false, function ($query) use ($mark) {
                $query->where('mark', intval($mark));
            })
            ->page();
        return $this->renderPage($log_list);
    }

    public function markAction($id) {
        MessageHistoryModel::where('id', $id)->updateBool('mark');
        return $this->renderData(true);
    }

    public function deleteAction($id) {
        MessageHistoryModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}