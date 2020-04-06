<?php
namespace Module\Template\Service\Admin;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Page;

class WeightController extends Controller {

    public function indexAction() {
        return $this->jsonSuccess(WeightModel::all());
    }

    public function settingAction($id) {
        return $this->jsonSuccess(PageWeightModel::find($id));
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
        return $this->jsonSuccess($data);
    }

    public function refreshAction($id) {
        $model = PageWeightModel::find($id);
        $data = $model->toArray();
        $data['html'] = (new Page(PageModel::find($model->page_id), true))
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

    public function editDialogAction($id) {
        return $this->jsonSuccess();
    }
}