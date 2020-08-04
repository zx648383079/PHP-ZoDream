<?php
namespace Module\Contact\Service\Api\Admin;

use Module\Contact\Domain\Model\SubscribeModel;

class SubscribeController extends Controller {
    public function indexAction() {
        $model_list = SubscribeModel::orderBy([
            'status' => 'asc',
            'id' => 'desc'
        ])->page();
        return $this->renderPage($model_list);
    }

}