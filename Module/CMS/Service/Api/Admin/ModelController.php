<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\ModelRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ModelController extends Controller {

    public function indexAction(string $keywords = '', int $type = 0) {
        return $this->renderPage(
            ModelRepository::getList($keywords, $type)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ModelRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
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
            ]);
            return $this->render(
                ModelRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ModelRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function allAction(int $type = 0) {
        return $this->renderData(
            ModelRepository::all($type)
        );
    }

    public function fieldAction(int $model) {
        return $this->renderData(
            ModelRepository::fieldList($model)
        );
    }

    public function fieldDetailAction(int $id) {
        try {
            return $this->render(
                ModelRepository::field($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function fieldSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'field' => 'required|string:0,100',
                'model_id' => 'required|int',
                'type' => 'string:0,20',
                'length' => 'int:0,999',
                'position' => 'int:0,999',
                'form_type' => 'int:0,999',
                'is_main' => 'int:0,9',
                'is_required' => 'int:0,9',
                'is_search' => 'int:0,9',
                'is_default' => 'int:0,9',
                'is_disable' => 'int:0,9',
                'is_system' => 'int:0,9',
                'match' => 'string:0,255',
                'tip_message' => 'string:0,255',
                'error_message' => 'string:0,255',
                'tab_name' => 'string:0,4',
                'setting' => '',
            ]);
            return $this->render(
                ModelRepository::fieldSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function fieldDeleteAction(int $id) {
        try {
            ModelRepository::fieldRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function fieldToggleAction(int $id, array $action) {
        try {
            return $this->render(ModelRepository::fieldToggle($id, $action));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function optionAction(string $type, int $id = 0) {
        return $this->renderData(
            ModelRepository::fieldOption($type, $id)
        );
    }
}