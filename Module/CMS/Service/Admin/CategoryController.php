<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Module;
use Zodream\Helpers\Json;

class CategoryController extends Controller {
    public function indexAction() {
        $model_list = CategoryModel::tree()->makeTreeForHtml();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = CategoryModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        $model_list = ModelModel::where('type', 0)->select('name', 'id')->all();
        $group_list = GroupModel::where('type', 0)->all();
        $cat_list = CategoryModel::tree()->makeTreeForHtml();
        if (!empty($id)) {
            $excludes = [$id];
            $cat_list = array_filter($cat_list, function ($item) use (&$excludes) {
                if (in_array($item['id'], $excludes)) {
                    return false;
                }
                if (in_array($item['parent_id'], $excludes)) {
                    $excludes[] = $item['id'];
                    return false;
                }
                return true;
            });
        }
        return $this->show(compact('model', 'model_list', 'cat_list', 'group_list'));
    }

    public function saveAction() {
        $model = new CategoryModel();
        if (!$model->load()) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->autoIsNew();
        if ($model->isNewRecord && empty($model->setting)) {
            $model->setting = serialize([]);
        }
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        CMSRepository::generateCategoryTable($model);
        return $this->renderData([
            'url' => $this->getUrl('category')
        ]);
    }

    public function deleteAction($id) {
        $items = CategoryModel::getChildrenWithParent($id);
        $cat = CategoryModel::find($id);
        $modelIds = CategoryModel::whereIn('id', $items)
            ->where('model_id', '>', 0)
            ->pluck('model_id');
        if (empty($modelIds)) {
            $model_list = ModelModel::whereIn('id', $modelIds)
                ->get();
            foreach ($model_list as $model) {
                $scene = CMSRepository::scene()->setModel($model);
                $ids = $scene->query()->whereIn('cat_id', $items)->pluck('id');
                $scene->remove($ids);
            }
        }
        CategoryModel::whereIn('id', $items)->delete();
        return $this->renderData([
            'url' => $this->getUrl('category')
        ]);
    }
}