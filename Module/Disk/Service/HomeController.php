<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Model\ShareModel;

class HomeController extends Controller {

    protected function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction() {
        $model_list = ShareModel::with('user')->where('mode', 'public')->orderBy('created_at desc')->page();
        return $this->show(compact('model_list'));
    }
}