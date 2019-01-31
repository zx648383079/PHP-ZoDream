<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Scene\SingleScene;
use Module\CMS\Module;
use Zodream\Infrastructure\Http\Response;

class ModelController extends Controller {
    public function indexAction() {
        $model_list = ModelModel::all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ModelModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        return $this->show(compact('model'));
    }

    public function saveAction($id = 0) {
        $model = ModelModel::findOrNew($id);
        if (!$model->load()) {
            return $this->jsonFailure('表单错误');
        }
        if ($id > 0) {
            $model->deleteAttribute('table');
        } elseif (ModelModel::where('`table`', $model->table)->count() > 0) {
            return $this->jsonFailure('表名已存在');
        }
        if (!$model->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        if ($id < 1) {
            Module::scene()->setModel($model)->initTable();
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('model')
        ]);
    }

    public function deleteAction($id) {
        $model = ModelModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('模型不存在');
        }
        $model->delete();
        ModelFieldModel::where('model_id', $id)->delete();
        Module::scene()->setModel($model)->removeTable();
        return $this->jsonSuccess([
            'url' => $this->getUrl('model')
        ]);
    }

    public function fieldAction($id) {
        $model = ModelModel::find($id);
        $model_list = ModelFieldModel::where('model_id', $id)->all();
        return $this->show(compact('model_list', 'model'));
    }

    public function createFieldAction() {
        return $this->runMethodNotProcess('editField', ['id' => null]);
    }

    public function editFieldAction($id, $model_id = null) {
        $model = ModelFieldModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        if (!$model->model_id) {
            $model->model_id = $model_id;
        }
        return $this->show(compact('model'));
    }

    public function saveFieldAction($id = 0) {
        $field = ModelFieldModel::findOrNew($id);
        if ($field->is_system > 0) {
            return $this->saveSystemField($field);
        }
        $field->load();
        if (ModelFieldModel::where('`field`', $field->field)
                ->where('id', '<>', $id)
                ->where('model_id', $field->model_id)
                ->count() > 0) {
            return $this->jsonFailure('字段已存在');
        }
        if (!$field->save()) {
            return $this->jsonFailure($field->getFirstError());
        }
        $scene = Module::scene()->setModel(ModelModel::find($field->model_id));
        if ($id > 0) {
            $scene->updateField($field);
        } else {
            $scene->addField($field);
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('model/field', ['id' => $field->model_id])
        ]);
    }

    public function deleteFieldAction($id) {
        $field = ModelFieldModel::find($id);
        if ($field->is_system > 0) {
            return $this->jsonFailure('系统自带字段禁止删除');
        }
        $field->delete();
        Module::scene()->setModel(ModelModel::find($field->model_id))
            ->removeField($field);
        return $this->jsonSuccess([
            'url' => $this->getUrl('model/field', ['id' => $field->model_id])
        ]);
    }

    public function toggleFieldAction($id, $name) {
        if (!in_array($name, ['is_disable'])) {
            return $this->jsonFailure('禁止操作此字段');
        }
        ModelFieldModel::query()->where('id', $id)->updateBool($name);
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function optionAction($type, $id = 0) {
        $this->layout = false;
        $model = ModelFieldModel::findOrNew($id);
        $field = SingleScene::newField($type);
        return $this->showContent($field->options($model));
    }

    /**
     * @param $model
     * @return Response
     * @throws \Exception
     */
    private function saveSystemField($model) {
        $model->name = app('request')->get('name');
        $model->save();
        return $this->jsonSuccess([
            'url' => $this->getUrl('model/field', ['id' => $model->model_id])
        ]);
    }
}