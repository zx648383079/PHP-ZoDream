<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\WeightModel;

use Module\Template\Domain\Page;
use Zodream\Route\Controller\Controller;

class WeightController extends Controller {

    public function indexAction() {
        return $this->jsonSuccess(WeightModel::all());
    }

    public function configAction($id) {
        return $this->jsonSuccess(PageWeightModel::find($id));
    }

    public function createAction() {
        $page_id = intval(app('request')->get('page_id'));
        $weight_id = intval(app('request')->get('weight_id'));
        $parent_id = intval(app('request')->get('parent_id'));
        $model = PageWeightModel::create([
            'page_id' => $page_id,
            'weight_id' => $weight_id,
            'parent_id' => $parent_id
        ]);
        $data = $model->toArray();
        $data['html'] = (new Page(PageModel::find($page_id)))
            ->renderWeight($model);
        return $this->jsonSuccess($data);
    }

    public function saveAction() {
        $pageWeight = PageWeightModel::saveFromPost();
        return $this->jsonSuccess($pageWeight);
    }

    public function destroyAction($id) {
        PageWeightModel::removeSelfAndChild(intval($id));
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
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}