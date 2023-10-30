<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CacheRepository;
use Module\CMS\Domain\Repositories\CategoryRepository;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\ThemeManager;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {
    public function indexAction() {
        $model_list = CategoryRepository::getList(CMSRepository::siteId());
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = CategoryModel::findOrNew($id);
        if (!$model->position) {
            $model->position = 99;
        }
        $model_list = ModelModel::where('type', 0)->select('name', 'id')->get();
        $group_list = GroupModel::where('type', 0)->get();
        $cat_list = CategoryRepository::all(CMSRepository::siteId());
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
        $template_list = $this->getThemeTemplate();
        return $this->show('edit', compact('model', 'model_list',
            'cat_list',
            'group_list', 'template_list'));
    }

    public function saveAction() {
        $model = new CategoryModel();
        if (!$model->load()) {
            return $this->renderFailure($model->getFirstError());
        }
        $model->autoIsNew();
        if ($model->isNewRecord && empty($model->setting)) {
            $model->setting = [];
        }
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        CMSRepository::generateCategoryTable($model);
        CacheRepository::onChannelUpdated(intval($model->id));
        return $this->renderData([
            'url' => $this->getUrl('category'),
            'no_jax' => true
        ]);
    }

    public function deleteAction(int $id) {
        $items = CategoryModel::getChildrenWithParent($id);
        // $cat = CategoryModel::find($id);
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
        CacheRepository::onChannelUpdated($id);
        return $this->renderData([
            'url' => $this->getUrl('category'),
            'no_jax' => true
        ]);
    }

    public function quicklyAction(Input $input) {
        if ($input->isAjax()) {
            $this->layout = '';
        }
        $model_list = ModelModel::where('type', 0)->select('name', 'id')->get();
        $group_list = GroupModel::where('type', 0)->get();
        $cat_list = CategoryRepository::all(CMSRepository::siteId());
        $template_list = $this->getThemeTemplate(true);
        return $this->show('quickly', compact('cat_list',
            'template_list', 'group_list', 'model_list'));
    }

    public function batchSaveAction(Input $input) {
        try {
            CategoryRepository::batchSave($input->validate([
                'parent_id' => 'int',
                'model_id' => 'int',
                'groups' => '',
                'category_template' => '',
                'list_template' => '',
                'show_template' => '',
                'open_comment' => 'int',
                'content' => 'required|string'
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => $this->getUrl('category')
        ], '添加成功');
    }

    protected function getThemeTemplate(bool $hasParent = false): array {
        $template_list = (new ThemeManager())->loadTemplates(CMSRepository::site()->theme);
        $prefix = ['' => '--继承模型--'];
        if ($hasParent) {
            $prefix['@parent'] = '--继承父级栏目--';
        }
        foreach ($template_list as $key => $items) {
            $template_list[$key] = array_merge($prefix, array_column($items, 'name',
                'value'));
        }
        return $template_list;
    }
}