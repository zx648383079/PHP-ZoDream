<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;

class FormController extends Controller {

    public function indexAction() {
        $model = $this->getModel();
        $field_list = ModelFieldModel::where('model_id', $model->id)->all();
        $scene = Module::scene()->setModel($model);
        return $this->show(compact('model', 'field_list', 'scene'));
    }

    public function saveAction() {
        $model = $this->getModel();
        if (empty($model) || $model->type < 1) {
            return $this->jsonFailure('表单数据错误');
        }
        $scene = Module::scene()->setModel($model);
        $data = app('request')->get();
        if (!$scene->insert($data)) {
            return $this->jsonFailure($scene->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => './'
        ]);
    }

    /**
     * @return ModelModel
     * @throws \Exception
     */
    protected function getModel() {
        if (app('request')->has('id')) {
            return ModelModel::find(intval(app('request')->get('id')));
        }
        return FuncHelper::model(app('request')->get('model'));
    }
}