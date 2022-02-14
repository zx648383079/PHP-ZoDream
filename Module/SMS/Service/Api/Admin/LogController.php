<?php
declare(strict_types=1);
namespace Module\SMS\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\SMS\Domain\Model\SmsLogModel;

class LogController extends Controller {

    public function indexAction(int $type = 0, string $keywords = '') {
        $model_list = SmsLogModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['mobile', 'content', 'ip']);
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type);
            })->page();
        return $this->renderPage($model_list);
    }

    public function deleteAction(int $id) {
        SmsLogModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}