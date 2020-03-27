<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Zodream\Route\Controller\RestController;

class BulletinController extends RestController {

    protected function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction($keywords = null, $status = 0) {
        $model_list = BulletinUserModel::with('bulletin')
            ->when(!empty($keywords), function ($query) {
                $ids = BulletinModel::where(function ($query) {
                    BulletinModel::search($query, 'title');
                })->pluck('id');
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('bulletin_id', $ids);
            })
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status - 1);
            })
            ->orderBy('status', 'asc')
            ->orderBy('bulletin_id', 'desc')->page();
        return $this->renderPage($model_list);
    }

}