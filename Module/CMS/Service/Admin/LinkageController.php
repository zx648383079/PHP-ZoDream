<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Html\Tree;

class LinkageController extends Controller {
    public function indexAction() {
        $model_list = LinkageModel::all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = LinkageModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new LinkageModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('linkage')
        ]);
    }

    public function deleteAction($id) {
        LinkageModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('linkage')
        ]);
    }

    public function dataAction($id, $parent_id = 0) {
        $model = LinkageModel::find($id);
        $model_list = LinkageDataModel::where('linkage_id', $id)->where('parent_id', $parent_id)->all();
        $parent = $parent_id > 0 ? LinkageDataModel::find($id) : null;
        return $this->show(compact('model_list', 'model', 'parent_id', 'parent'));
    }

    public function createDataAction($linkage_id, $parent_id = null) {
        $id = 0;
        return $this->runMethodNotProcess('editData', compact('id', 'linkage_id', 'parent_id'));
    }

    public function editDataAction($id, $linkage_id = null, $parent_id = null) {
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
        return $this->show(compact('model'));
    }

    public function saveDataAction() {
        $model = new LinkageDataModel();
        if (!$model->load() || !$model->autoIsNew()->createFullName()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('linkage/data', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

    public function deleteDataAction($id) {
        $model = LinkageDataModel::find($id);
        $model->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('linkage/data', ['id' => $model->linkage_id, 'parent_id' => $model->parent_id])
        ]);
    }

    public function treeAction($id) {
        $data = cache()->getOrSet('cms_linkage_tree_'.$id, function () use ($id) {
            $tree = new Tree(LinkageDataModel::query()->where('linkage_id', $id)
                ->select('id', 'name', 'parent_id')->asArray()->all());
            return $tree->makeIdTree();
        }, 60);
        return $this->jsonSuccess($data);
    }

}