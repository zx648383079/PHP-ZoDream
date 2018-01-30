<?php
namespace Module\WeChat\Service;

use Module\ModuleController;
use Module\WeChat\Domain\Model\MediaModel;

class MediaController extends ModuleController {

    public function indexAction($type = null) {
        $model_list = MediaModel::when(!empty($type), function ($query) use ($type) {
            $query->where('type', $type);
        })->page();
        return $this->show(compact('model_list'));
    }
}