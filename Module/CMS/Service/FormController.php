<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;

class FormController extends Controller {

    public function indexAction($id) {
        $model = ModelModel::find($id);
        $field_list = ModelFieldModel::where('model_id', $model->id)->all();
        $scene = Module::scene()->setModel($model);
        return $this->show(compact('model', 'field_list', 'scene'));
    }

    public function saveAction($id) {
        $model = ModelModel::find($id);
        if (empty($model) || $model->type < 1) {
            return $this->jsonFailure('表单数据错误');
        }
        $field_list = ModelFieldModel::where('model_id', $model->id)->all();
        $scene = Module::scene()->setModel($model);
        $data = app('request')->get();
        $scene->insert($data, $field_list);
        return $this->jsonSuccess([
            'url' => './'
        ]);
    }
}