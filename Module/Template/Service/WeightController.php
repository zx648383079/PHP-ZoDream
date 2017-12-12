<?php
namespace Module\Template\Service;

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\WeightModel;
use Zodream\Route\Controller\Controller;

class WeightController extends Controller {

    public function indexAction() {
        return $this->jsonSuccess(WeightModel::all());
    }

    public function configAction($id) {
        return $this->jsonSuccess(PageWeightModel::find($id));
    }

    public function saveAction() {
        $pageWeight = PageWeightModel::saveFromPost();
        return $this->jsonSuccess($pageWeight);
    }

    public function destroyAction($id) {
        PageWeightModel::where('id', $id)->delete();
        return $this->jsonSuccess();
    }
}