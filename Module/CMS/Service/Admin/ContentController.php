<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\Auth\Domain\Events\ManageAction;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CategoryRepository;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ContentRepository;
use Module\CMS\Domain\Repositories\LocaleRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ContentController extends Controller {
    public function indexAction(int $cat_id = 0, int $model_id = 0,
                                string $keywords = '', int $parent_id = 0, int $page = 1) {
        if ($cat_id <= 0 && $model_id <= 0) {
            return $this->redirectWithMessage('./@admin', '参数错误');
        }
        $context = CMSRepository::context();
        $cat = null;
        if ($cat_id > 0) {
            $cat = $context->channelBuilder()->where('site_id', $context->id())
            ->where('id', $cat_id)->first();
            if ($model_id < 1) {
                $model_id = $cat->model_id;
            }
        }
        $model = ModelModel::find($model_id);
        if (empty($model)) {
            return $this->redirectWithMessage('./@admin', '栏目模型配置错误');
        }
        try {
            $model_list = ContentRepository::getList($context->id(), $cat_id, $keywords, $parent_id, $model_id, $page);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl(''), $ex->getMessage());
        }
        return $this->show(compact('model_list', 'cat', 'keywords', 'parent_id', 'model'));
    }

    public function createAction(int $model_id, int $cat_id = 0, int $site_id = 0, int $parent_id = 0, int $locale = 0) {
        if ($locale > 0) {
            $model = ModelModel::find($model_id);
            if (empty($model)) {
                return $this->redirectWithMessage('./@admin', '栏目模型配置错误');
            }
            $id = LocaleRepository::articleConvert($site_id, $model, $locale);
            if ($id > 0) {
                return $this->edit($id, $model_id, $site_id);
            }
            
            if ($parent_id > 0) {
                $parent_id = LocaleRepository::articleConvert($site_id, $model, $parent_id);
                if ($parent_id === 0) {
                    return $this->redirectWithMessage(url('./@admin/category'), '上级文章不存在！');
                }
            }
            if ($cat_id > 0) {
                $cat_id = LocaleRepository::channelConvert($site_id, $cat_id);
            }
        }
        return $this->edit(0, $model_id, $cat_id, $site_id, $parent_id, $locale);
    }

    /**
     * 为了适配可能出现的多表
     * @param int $id
     * @param int $model_id
     * @return \Zodream\Infrastructure\Contracts\Http\Output
     * @throws \Exception
     */
    public function editAction(int $id, int $model_id) {
        return $this->edit($id, $model_id);
    }

    private function edit(int $id, int $model_id, int $cat_id = 0, int $site_id = 0, int $parent_id = 0, int $locale = 0) {
        $context = CMSRepository::context();
        if ($site_id > 0 && $context->id() !== $site_id) {
            $context = CMSRepository::contextFrom($site_id);
        }
        $cat = $cat_id > 0 ? $context->channelBuilder()->where('site_id', $site_id)
            ->where('id', $cat_id)->first() : null;
        $model = ModelModel::find($model_id);
        if (empty($model)) {
            return $this->redirectWithMessage('./@admin', '栏目模型配置错误');
        }
        $scene = $context->scene()->setModel($model);
        $data = $id > 0 ? $scene->find($id) : [
            'parent_id' => $parent_id,
            'site_id' => $context->id(),
            'locale_group_id' => $locale
        ];
        $tab_list = ModelRepository::fieldGroupByTab($model->id);
        $languageItems = LocaleRepository::siteOptions($context->source());
        $cat_list = CategoryRepository::all($context);
        if ($model->edit_template) {
            CMSRepository::registerView($context);
            return $this->show($model->edit_template, compact('id',
                'cat_id', 'cat', 'scene', 'model',
                'data', 'tab_list', 'languageItems', 'cat_list'));
        }
        return $this->show('edit', compact('id',
            'cat_id', 'cat', 'scene', 'model',
            'data', 'tab_list', 'languageItems', 'cat_list'));
    }

    public function saveAction(int $id, int $cat_id, int $site_id, int $model_id, int $locale_group_id = 0) {
        $context = CMSRepository::context();
        if ($site_id > 0 && $context->id() !== $site_id) {
            $context = CMSRepository::contextFrom($site_id);
        }
        $model = ModelModel::find($model_id);
        $scene = $context->scene()->setModel($model);
        $data = request()->get();
        try {
            if ($id > 0) {
                $scene->update($id, $data);
            } else {
                $id = $scene->insert($data);
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        if ($id > 0) {
            if ($locale_group_id > 0) {
                $scene->query()->whereIn('id', [$locale_group_id, $id])
                    ->update([
                        'locale_group_id' => $locale_group_id
                    ]);
            }
            event(new ManageAction('cms_content_edit', '', 33, $id));
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

    public function deleteAction(int|array $id, int $model_id, int $cat_id = 0) {
        //$cat = CategoryModel::find($cat_id);
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::context()->scene()
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
        $context = CMSRepository::context();
        $cat = $context->channelBuilder()->where('site_id', $context->id())->where('id', $cat_id)->first();
        $model = ModelModel::find($model_id);
        $scene = $context->scene()->setModel($model);
        $data = $scene->find($id);
        return $this->show('dialog', compact('id',
            'cat_id', 'cat', 'scene', 'model',
            'data'));
    }

    public function searchAction(int $model = 0, string $keywords = '', int $channel = 0,
                                 array|int $id = [], int $page = 1, int $perPage = 20) {
        try {
            return $this->renderPage(ContentRepository::search(
                CMSRepository::context()->id(),
                $model, $keywords, $channel, $id, $page, $perPage));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }
}