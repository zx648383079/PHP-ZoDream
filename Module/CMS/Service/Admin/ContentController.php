<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;
use Zodream\Infrastructure\Http\Response;

class ContentController extends Controller {
    public function indexAction($cat_id, $keywords = null, $parent_id = 0) {
        $parent_id = intval($parent_id);
        $cat = CategoryModel::find($cat_id);
        $scene = Module::scene()->setModel($cat->model);
        if ($parent_id > 0 && $cat->model->child_model > 0) {
            $scene->setModel(ModelModel::find($cat->model->child_model));
        } else {
            $parent_id = 0;
        }
        $model_list = $scene->search($keywords,
            compact('cat_id', 'parent_id'), 'id desc');
        return $this->show(compact('model_list', 'cat', 'keywords', 'parent_id'));
    }

    public function createAction($cat_id, $parent_id = 0) {
        return $this->runMethodNotProcess('edit', [
            'id' => null,
            'cat_id' => $cat_id,
            'parent_id' => $parent_id
        ]);
    }

    /**
     * 为了适配可能出现的多表
     * @param $id
     * @param $cat_id
     * @param int $parent_id
     * @return Response
     * @throws \Exception
     */
    public function editAction($id, $cat_id, $parent_id = 0) {
        $cat = CategoryModel::find($cat_id);
        $scene = Module::scene()->setModel($cat->model);
        $model_id = $cat->model_id;
        if ($parent_id > 0 && $cat->model->child_model > 0) {
            $model_id = $cat->model->child_model;
            $scene->setModel(ModelModel::find($cat->model->child_model));
        } else {
            $parent_id = 0;
        }
        $data = $id > 0 ? $scene->find($id) : [
            'parent_id' => $parent_id
        ];
        $tab_list = ModelFieldModel::tabGroups($model_id);
        return $this->show(compact('id', 'cat_id', 'cat', 'scene', 'data', 'tab_list'));
    }

    public function saveAction($id, $cat_id) {
        $cat = CategoryModel::find($cat_id);
        $scene = Module::scene()->setModel($cat->model);
        $data = app('request')->get();
        if (isset($data['parent_id']) &&
            $data['parent_id'] > 0
            && $cat->model->child_model > 0) {
            $scene->setModel(ModelModel::find($cat->model->child_model));
        } else {
            $data['parent_id'] = 0;
        }
        if ($id > 0) {
            $scene->update($id, $data);
        } else {
            $scene->insert($data);
        }
        if ($scene->hasError()) {
            return $this->jsonFailure($scene->getFirstError());
        }
        $queries = [
            'cat_id' => $cat_id,
        ];
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            $queries['parent_id'] = $data['parent_id'];
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('content', $queries)
        ]);
    }

    public function deleteAction($id, $cat_id, $parent_id = 0) {
        $cat = CategoryModel::find($cat_id);
        $scene = Module::scene()
            ->setModel($cat->model);
        if ($parent_id > 0 && $cat->model->child_model > 0) {
            $scene->setModel(ModelModel::find($cat->model->child_model));
        }
        $data = $scene->find($id);
        if (!empty($data)) {
            $scene->remove($id);
        }
        $queries = [
            'cat_id' => $cat_id,
        ];
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            $queries['parent_id'] = $data['parent_id'];
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('content', $queries)
        ]);
    }
}