<?php
namespace Module\SMS\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\SMS\Domain\Model\SmsLogModel;

class LogController extends Controller {

    public function indexAction($type = 0, $keywords = '') {
        $model_list = SmsLogModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['mobile', 'content', 'ip']);
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type);
            })->page();
        return $this->renderPage($model_list);
    }

    public function deleteAction($id) {
        SmsLogModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}