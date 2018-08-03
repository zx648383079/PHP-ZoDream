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

    public function createAction() {
        $page = intval(app('request')->get('page'));
        $weight = intval(app('request')->get('weight'));
        $parent_id = intval(app('request')->get('parent_id'));
        return $this->jsonSuccess(PageWeightModel::create([
            'page_id' => $page,
            'weight_id' => $weight,
            'parent_id' => $parent_id
        ]));
    }

    public function saveAction() {
        $pageWeight = PageWeightModel::saveFromPost();
        return $this->jsonSuccess($pageWeight);
    }

    public function destroyAction($id) {
        PageWeightModel::where('id', $id)->delete();
        return $this->jsonSuccess();
    }

    public function thumbAction($id) {
        WeightModel::find($id);
    }

    public function installAction() {
        $data = WeightModel::findWeights();
        foreach ($data as $item) {
            if (WeightModel::isInstalled($item['name'])) {
                continue;
            }
            WeightModel::install($item);
        }
        return $this->jsonSuccess();
    }
}