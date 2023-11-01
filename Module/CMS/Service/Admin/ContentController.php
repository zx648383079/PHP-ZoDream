<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\Auth\Domain\Events\ManageAction;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ContentRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ContentController extends Controller {
    public function indexAction(int $cat_id = 0, int $model_id = 0,
                                string $keywords = '', int $parent_id = 0, int $page = 1) {
        if ($cat_id <= 0 && $model_id <= 0) {
            return $this->redirectWithMessage('./@admin', '参数错误');
        }
        $cat = null;
        if ($cat_id > 0) {
            $cat = CategoryModel::find($cat_id);
            if ($model_id < 1) {
                $model_id = $cat->model_id;
            }
        }
        $model = ModelModel::find($model_id);
        if (empty($model)) {
            return $this->redirectWithMessage('./@admin', '栏目模型配置错误');
        }
        try {
            $model_list = ContentRepository::getList(CMSRepository::siteId(), $cat_id, $keywords, $parent_id, $model_id, $page);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl(''), $ex->getMessage());
        }
        return $this->show(compact('model_list', 'cat', 'keywords', 'parent_id', 'model'));
    }

    public function createAction(int $model_id, int $cat_id = 0, int $parent_id = 0) {
        return $this->editAction(0, $model_id, $cat_id, $parent_id);
    }

    /**
     * 为了适配可能出现的多表
     * @param int $id
     * @param int $cat_id
     * @param int $model_id
     * @param int $parent_id
     * @return \Zodream\Infrastructure\Contracts\Http\Output
     * @throws \Exception
     */
    public function editAction(int $id, int $model_id, int $cat_id = 0, int $parent_id = 0) {
        $cat = $cat_id > 0 ? CategoryModel::find($cat_id) : null;
        $model = ModelModel::find($model_id);
        if (empty($model)) {
            return $this->redirectWithMessage('./@admin', '栏目模型配置错误');
        }
        $scene = CMSRepository::scene()->setModel($model);
        $data = $id > 0 ? $scene->find($id) : [
            'parent_id' => $parent_id
        ];
        $tab_list = ModelRepository::fieldGroupByTab($model->id);
        if ($model->edit_template) {
            CMSRepository::registerView();
            return $this->show($model->edit_template, compact('id',
                'cat_id', 'cat', 'scene', 'model',
                'data', 'tab_list'));
        }
        return $this->show('edit', compact('id',
            'cat_id', 'cat', 'scene', 'model',
            'data', 'tab_list'));
    }

    public function saveAction(int $id, int $cat_id, int $model_id) {
        //$cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = request()->get();
        try {
            if ($id > 0) {
                $scene->update($id, $data);
            } else {
                $scene->insert($data);
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        event(new ManageAction('cms_content_edit', '', 33, $id));
        $queries = [
            'model_id' => $model_id
        ];
        if (config('view.cms_menu_mode', 0) < 1) {
            $queries['cat_id'] = $cat_id;
        }
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            $queries['parent_id'] = $data['parent_id'];
        }
        return $this->renderData([
            'url' => $this->getUrl('content', $queries)
        ]);
    }

    public function deleteAction(int|array $id, int $model_id, int $cat_id = 0) {
        //$cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()
            ->setModel($model);
        $data = $scene->find(is_array($id) ? intval(current($id)) : intval($id));
        if (!empty($data)) {
            $scene->remove($id);
        }
        $queries = [
            'model_id' => $model_id
        ];
        if (config('view.cms_menu_mode', 0) < 1) {
            $queries['cat_id'] = $cat_id;
        }
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            $queries['parent_id'] = $data['parent_id'];
        }
        return $this->renderData([
            'url' => $this->getUrl('content', $queries)
        ]);
    }

    public function dialogAction(Input $input, int $id, int $cat_id, int $model_id) {
        if ($input->isAjax()) {
            $this->layout = '';
        }
        $cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = $scene->find($id);
        return $this->show('dialog', compact('id',
            'cat_id', 'cat', 'scene', 'model',
            'data'));
    }

    public function searchAction(int $model = 0, string $keywords = '', int $channel = 0,
                                 array|int $id = [], int $page = 1, int $perPage = 20) {
        try {
            return $this->renderPage(ContentRepository::search(
                CMSRepository::siteId(),
                $model, $keywords, $channel, $id, $page, $perPage));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }
}