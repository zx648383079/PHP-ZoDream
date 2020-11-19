<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Page;
use Module\Template\Domain\Weight;
use Zodream\Infrastructure\Http\Request;

class WeightController extends Controller {

    public function indexAction() {
        return $this->renderData(WeightModel::all());
    }

    public function settingAction($id) {
        return $this->renderData(PageWeightModel::find($id));
    }

    public function createAction() {
        $page_id = intval(app('request')->get('page_id'));
        $pageModel = PageModel::find($page_id);
        $weight_id = intval(app('request')->get('weight_id'));
        $parent_id = intval(app('request')->get('parent_id'));
        $model = PageWeightModel::create([
            'page_id' => $pageModel->id,
            'theme_weight_id' => $weight_id,
            'parent_id' => $parent_id,
            'site_id' => $pageModel->site_id
        ]);
        $data = $model->toArray();
        $data['html'] = (new Page($pageModel, true))
            ->renderWeight($model);
        return $this->renderData($data);
    }

    public function refreshAction($id) {
        $model = PageWeightModel::find($id);
        $data = $model->toArray();
        $data['html'] = (new Page(PageModel::find($model->page_id), true))
            ->renderWeight($model);
        return $this->renderData($data);
    }

    public function saveAction($id) {
        $model = PageWeightModel::find($id);
        $model->saveFromPost();
        $data = $model->toArray();
        $data['html'] = (new Page(PageModel::find($model->page_id), true))
            ->renderWeight($model);
        return $this->renderData($data);
    }

    public function destroyAction($id) {
        PageWeightModel::removeSelfAndChild(intval($id));
        return $this->renderData();
    }

    public function thumbAction($id) {
        PageWeightModel::find($id);
    }

    public function editDialogAction($id) {
        $model = PageWeightModel::find($id);
        $html = (new Weight($model))->newWeight()->renderConfig($model);
        return $this->renderData(compact('html'));
    }
}