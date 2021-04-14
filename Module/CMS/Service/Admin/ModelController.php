<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\Auth\Domain\Events\ManageAction;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Module\CMS\Domain\Scene\SingleScene;
use Zodream\Infrastructure\Contracts\Http\Input;

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

    public function saveAction(Input $input) {
        try {
            ModelRepository::save($input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'table' => 'required|string:0,100',
                'type' => 'int:0,9',
                'position' => 'int:0,999',
                'child_model' => 'int',
                'category_template' => 'string:0,20',
                'list_template' => 'string:0,20',
                'show_template' => 'string:0,20',
                'setting' => '',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('model')
        ]);
    }

    public function deleteAction(int $id) {
        try {
            ModelRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('model')
        ]);
    }

    public function fieldAction(int $id) {
        $model = ModelModel::find($id);
        $model_list = ModelRepository::fieldList($id);
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
        $tab_list = ModelRepository::fieldTab($model->model_id);
        if (empty($model->tab_name) || !in_array($model->tab_name, $tab_list)) {
            $model->tab_name = $tab_list[$model->is_main > 0? 0 : 1];
        }
        return $this->show('editField', compact('model', 'tab_list'));
    }

    public function saveFieldAction(Input $input) {
        try {
            $field = ModelRepository::fieldSave($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('model/field', ['id' => $field->model_id])
        ]);
    }

    public function deleteFieldAction(int $id) {
        try {
            $field = ModelRepository::fieldRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('model/field', ['id' => $field->model_id])
        ]);
    }

    public function toggleFieldAction(int $id, string $name) {
        try {
            ModelRepository::fieldToggle($id, [$name]);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function optionAction(string $type, int $id = 0) {
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