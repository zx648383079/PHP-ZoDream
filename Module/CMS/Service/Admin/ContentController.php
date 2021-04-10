<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\Auth\Domain\Events\ManageAction;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ModelRepository;

class ContentController extends Controller {
    public function indexAction(int $cat_id, int $model_id = 0, string $keywords = '', int $parent_id = 0) {
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

    public function createAction(int $cat_id, int $model_id, int $parent_id = 0) {
        return $this->editAction(0, $cat_id, $model_id, $parent_id);
    }

    /**
     * 为了适配可能出现的多表
     * @param $id
     * @param $cat_id
     * @param $model_id
     * @param int $parent_id
     * @throws \Exception
     */
    public function editAction(int $id, int $cat_id, int $model_id, int $parent_id = 0) {
        $cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = $id > 0 ? $scene->find($id) : [
            'parent_id' => $parent_id
        ];
        $tab_list = ModelRepository::fieldGroupByTab($model->id);
        return $this->show('edit', compact('id',
            'cat_id', 'cat', 'scene', 'model',
            'data', 'tab_list'));
    }

    public function saveAction(int $id, int $cat_id, int $model_id) {
        //$cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = request()->get();
        if ($id > 0) {
            $scene->update($id, $data);
        } else {
            $scene->insert($data);
        }
        event(new ManageAction('cms_content_edit', '', 33, $id));
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

    public function deleteAction(int $id, int $cat_id, int $model_id) {
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