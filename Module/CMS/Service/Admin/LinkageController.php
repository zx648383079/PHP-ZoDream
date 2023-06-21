<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Repositories\LinkageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class LinkageController extends Controller {
    public function indexAction() {
        $model_list = LinkageRepository::getList();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = LinkageModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'type' => 'int:0,9',
                'code' => 'required|string:0,20',
            ]);
            LinkageRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('linkage')
        ]);
    }

    public function deleteAction(int $id) {
        LinkageRepository::remove($id);
        return $this->renderData([
            'url' => $this->getUrl('linkage')
        ]);
    }

    public function dataAction(int $id, int $parent_id = 0) {
        $model = LinkageModel::find($id);
        $model_list = LinkageRepository::dataList($id, '', $parent_id);
        $parent = $parent_id > 0 ? LinkageDataModel::find($id) : null;
        return $this->show(compact('model_list', 'model', 'parent_id', 'parent'));
    }

    public function createDataAction(int $linkage_id, int $parent_id = 0) {
        return $this->editDataAction(0, $linkage_id, $parent_id);
    }

    public function editDataAction(int $id, int $linkage_id = 0, int $parent_id = 0) {
        $model = LinkageDataModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        if (!$model->linkage_id) {
            $model->linkage_id = $linkage_id;
        }
        if (!$model->parent_id) {
            $model->parent_id = $parent_id;
        }
        return $this->show('editData', compact('model'));
    }

    public function saveDataAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'linkage_id' => 'required|int',
                'name' => 'required|string:0,100',
                'parent_id' => 'int',
                'position' => 'int:0,999',
                'description' => 'string:0,255',
                'thumb' => 'string:0,255',
            ]);
            $model = LinkageRepository::dataSave($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('linkage/data', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

    public function deleteDataAction(int $id) {
        $model = LinkageDataModel::find($id);
        $model->delete();
        return $this->renderData([
            'url' => $this->getUrl('linkage/data', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

    public function treeAction(int $id) {
        return $this->renderData(LinkageRepository::dataTree($id));
    }



}