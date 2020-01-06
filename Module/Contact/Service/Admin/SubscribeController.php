<?php
namespace Module\Contact\Service\Admin;

use Module\Contact\Domain\Model\SubscribeModel;

class SubscribeController extends Controller {
    public function indexAction() {
        $model_list = SubscribeModel::orderBy([
            'status' => 'asc',
            'id' => 'desc'
        ])->page();
        return $this->show(compact('model_list'));
    }

}