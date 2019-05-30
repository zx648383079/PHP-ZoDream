<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;
use Zodream\Infrastructure\Http\Response;

class ContentController extends Controller {
    public function indexAction($cat_id, $keywords = null) {
        $cat = CategoryModel::find($cat_id);
        $scene = Module::scene()->setModel($cat->model);
        $model_list = $scene->search($keywords, $cat_id, 'id desc');
        return $this->show(compact('model_list', 'cat'));
    }

    public function createAction($cat_id) {
        return $this->runMethodNotProcess('edit', ['id' => null, 'cat_id' => $cat_id]);
    }

    /**
     * 为了适配可能出现的多表
     * @param $id
     * @param $cat_id
     * @return Response
     * @throws \Exception
     */
    public function editAction($id, $cat_id) {
        $cat = CategoryModel::find($cat_id);
        $field_list = ModelFieldModel::where('model_id', $cat->model_id)->orderBy([
            'position' => 'asc',
            'id' => 'asc'
        ])->all();
        $scene = Module::scene()->setModel($cat->model);
        $data = $id > 0 ? $scene->find($id) : [];
        return $this->show(compact('id', 'cat_id', 'cat', 'scene', 'data', 'field_list'));
    }

    public function saveAction($id, $cat_id) {
        $cat = CategoryModel::find($cat_id);
        $field_list = ModelFieldModel::where('model_id', $cat->model_id)->all();
        $scene = Module::scene()->setModel($cat->model);
        $data = app('request')->get();
        if ($id > 0) {
            $scene->update($id, $data, $field_list);
        } else {
            $scene->insert($data, $field_list);
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('content', ['cat_id' => $cat_id])
        ]);
    }

    public function deleteAction($id, $cat_id) {
        $cat = CategoryModel::find($cat_id);
        $scene = Module::scene()->setModel($cat->model)->remove($id);
        return $this->jsonSuccess([
            'url' => $this->getUrl('content', ['cat_id' => $cat_id])
        ]);
    }
}