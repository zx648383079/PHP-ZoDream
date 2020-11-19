<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Module;
use Zodream\Infrastructure\Http\Response;

class ContentController extends Controller {
    public function indexAction($cat_id, $model_id = 0, $keywords = null, $parent_id = 0) {
        $parent_id = intval($parent_id);
        $cat = CategoryModel::find($cat_id);
        if ($model_id < 1) {
            $model_id = $cat->model_id;
        }
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $model_list = $scene->search($keywords,
            compact('cat_id', 'parent_id'), 'id desc');
        return $this->show(compact('model_list', 'cat', 'keywords', 'parent_id', 'model'));
    }

    public function createAction($cat_id, $model_id, $parent_id = 0) {
        $id = 0;
        return $this->runMethodNotProcess('edit', compact('id', 'cat_id', 'model_id', 'parent_id'));
    }

    /**
     * 为了适配可能出现的多表
     * @param $id
     * @param $cat_id
     * @param $model_id
     * @param int $parent_id
     * @return Response
     * @throws \Exception
     */
    public function editAction($id, $cat_id, $model_id, $parent_id = 0) {
        $cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = $id > 0 ? $scene->find($id) : [
            'parent_id' => $parent_id
        ];
        $tab_list = ModelFieldModel::tabGroups($model->id);
        return $this->show(compact('id',
            'cat_id', 'cat', 'scene', 'model',
            'data', 'tab_list'));
    }

    public function saveAction($id, $cat_id, $model_id) {
        //$cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = app('request')->get();
        if ($id > 0) {
            $scene->update($id, $data);
        } else {
            $scene->insert($data);
        }
        if ($scene->hasError()) {
            return $this->renderFailure($scene->getFirstError());
        }
        $queries = [
            'cat_id' => $cat_id,
            'model_id' => $model_id
        ];
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            $queries['parent_id'] = $data['parent_id'];
        }
        return $this->renderData([
            'url' => $this->getUrl('content', $queries)
        ]);
    }

    public function deleteAction($id, $cat_id, $model_id) {
        //$cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()
            ->setModel($model);
        $data = $scene->find($id);
        if (!empty($data)) {
            $scene->remove($id);
        }
        $queries = [
            'cat_id' => $cat_id,
            'model_id' => $model_id
        ];
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            $queries['parent_id'] = $data['parent_id'];
        }
        return $this->renderData([
            'url' => $this->getUrl('content', $queries)
        ]);
    }
}