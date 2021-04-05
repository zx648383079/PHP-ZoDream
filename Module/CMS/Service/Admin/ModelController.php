<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\Auth\Domain\Events\ManageAction;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Scene\SingleScene;

class ModelController extends Controller {
    public function indexAction() {
        $model_list = ModelModel::all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = ModelModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        $model_list = ModelModel::query()
            ->where('type', 0)
            ->whereNotIn('id', [$id])->get(['id', 'name']);
        return $this->show('edit', compact('model', 'model_list'));
    }

    public function saveAction(int $id = 0) {
        $model = ModelModel::findOrNew($id);
        if (!$model->load()) {
            return $this->renderFailure('表单错误');
        }
        if (!request()->has('setting')) {
            $model->setting = null;
        }
        if ($id > 0) {
            $model->deleteAttribute('table');
        } elseif (ModelModel::where('`table`', $model->table)->count() > 0) {
            return $this->renderFailure('表名已存在');
        }
        event(new ManageAction('cms_model_edit', '', 32, $id));
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        if ($id < 1) {
            CMSRepository::scene()->setModel($model)->initModel();
        }
        return $this->renderData([
            'url' => $this->getUrl('model')
        ]);
    }

    public function deleteAction(int $id) {
        $model = ModelModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('模型不存在');
        }
        $model->delete();
        ModelFieldModel::where('model_id', $id)->delete();
        CMSRepository::removeModel($model);
        return $this->renderData([
            'url' => $this->getUrl('model')
        ]);
    }

    public function fieldAction(int $id) {
        $model = ModelModel::find($id);
        $model_list = ModelFieldModel::where('model_id', $id)->all();
        return $this->show(compact('model_list', 'model'));
    }

    public function createFieldAction(int $model_id) {
        return $this->editFieldAction(0, $model_id);
    }

    public function editFieldAction(int $id, int $model_id = 0) {
        $model = ModelFieldModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        if (!$model->model_id) {
            $model->model_id = $model_id;
        }
        $tab_list = ModelFieldModel::tabItems($model->model_id);
        if (empty($model->tab_name) || !in_array($model->tab_name, $tab_list)) {
            $model->tab_name = $tab_list[$model->is_main > 0? 0 : 1];
        }
        return $this->show('editField', compact('model', 'tab_list'));
    }

    public function saveFieldAction(int $id = 0) {
        $field = ModelFieldModel::findOrNew($id);
        if ($field->is_system > 0) {
            return $this->saveSystemField($field);
        }
        $field->load();
        if (ModelFieldModel::where('`field`', $field->field)
                ->where('id', '<>', $id)
                ->where('model_id', $field->model_id)
                ->count() > 0) {
            return $this->renderFailure('字段已存在');
        }
        $old = $field->getOldAttribute('field');
        if ($field->is_main > 0
            && $field->is_system < 1
            && CMSRepository::scene() instanceof SingleScene) {
            $field->is_main = 0;
        }
        if (!$field->save()) {
            return $this->renderFailure($field->getFirstError());
        }
        $scene = CMSRepository::scene()->setModel(ModelModel::find($field->model_id));
        if ($id > 0) {
            $field->setOldAttribute([
                'field' => $old
            ]);
            $scene->updateField($field);
        } else {
            $scene->addField($field);
        }
        return $this->renderData([
            'url' => $this->getUrl('model/field', ['id' => $field->model_id])
        ]);
    }

    public function deleteFieldAction(int $id) {
        $field = ModelFieldModel::find($id);
        if ($field->is_system > 0) {
            return $this->renderFailure('系统自带字段禁止删除');
        }
        $field->delete();
        CMSRepository::scene()->setModel(ModelModel::find($field->model_id))
            ->removeField($field);
        return $this->renderData([
            'url' => $this->getUrl('model/field', ['id' => $field->model_id])
        ]);
    }

    public function toggleFieldAction(int $id, string $name) {
        if (!in_array($name, ['is_disable'])) {
            return $this->renderFailure('禁止操作此字段');
        }
        ModelFieldModel::query()->where('id', $id)->updateBool($name);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function optionAction(int $type, int $id = 0) {
        $this->layout = false;
        $model = ModelFieldModel::findOrNew($id);
        $field = SingleScene::newField($type);
        return $this->showContent($field->options($model));
    }

    /**
     * @param $model
     * @throws \Exception
     */
    private function saveSystemField($model) {
        $model->name = request()->get('name');
        $model->save();
        return $this->renderData([
            'url' => $this->getUrl('model/field', ['id' => $model->model_id])
        ]);
    }
}