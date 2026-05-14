<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\GroupModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CategoryRepository;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\LocaleRepository;
use Module\CMS\Domain\ThemeManager;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {
    public function indexAction() {
        try {
            $context = CMSRepository::context();
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl('site'), '请先点击站点“管理”进行下一步操作');
        }
        $model_list = CategoryRepository::getList($context);
        return $this->show(compact('model_list'));
    }

    public function createAction(int $site_id = 0, int $parent_id = 0, int $locale = 0) {
        if ($locale > 0) {
            $id = LocaleRepository::channelConvert($site_id, $locale);
            if ($id > 0) {
                return $this->edit($id, $site_id);
            }
            if ($parent_id > 0) {
                $parent_id = LocaleRepository::channelConvert($site_id, $parent_id);
                if ($parent_id === 0) {
                    return $this->redirectWithMessage(url('./@admin/category'), '上级栏目不存在！');
                }
            }
        }
        return $this->edit(0, $site_id, $parent_id, $locale);
    }

    public function editAction(int $id) {
        return $this->edit($id);
    }

    private function edit(int $id, int $site_id = 0, int $parent_id = 0, int $locale = 0) {
        $context = CMSRepository::context();
        if ($site_id > 0 && $context->id() !== $site_id) {
            $context = CMSRepository::contextFrom($site_id);
        }
        $model = CategoryRepository::getOrCreate($context, $id);
        if (!$model->position) {
            $model->position = 99;
        }
        if ($id === 0) {
            $model->site_id = $context->id();
            $model->parent_id = $parent_id;
            $model->locale_group_id = $locale;
        }
        $model_list = ModelModel::where('type', 0)->select('name', 'id')->get();
        $group_list = GroupModel::where('type', 0)->get();
        $cat_list = CategoryRepository::all($context);
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
        $languageItems = LocaleRepository::siteOptions($context->source());
        return $this->show('edit', compact('model', 'model_list',
            'cat_list',
            'group_list', 'template_list', 'languageItems'));
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'title' => 'required|string:0,100',
                'type' => 'int:0,9',
                'model_id' => 'int',
                'site_id' => 'int',
                'locale_group_id' => 'int',
                'parent_id' => 'int',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'image' => 'string:0,100',
                'thumb' => 'string:0,100',
                'content' => '',
                'url' => 'string:0,100',
                'position' => 'int:0,999',
                'groups' => '',
                'category_template' => 'string:0,20',
                'list_template' => 'string:0,20',
                'show_template' => 'string:0,20',
                'setting' => '',
            ]);
            CategoryRepository::save(CMSRepository::context(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('category'),
            'no_jax' => true
        ]);
    }

    public function deleteAction(int $id) {
        try {
            CategoryRepository::remove(CMSRepository::context(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('category'),
            'no_jax' => true
        ]);
    }

    public function unlinkAction(int $id) {
        LocaleRepository::channelUnlink(CMSRepository::context(), $id);
        return $this->renderData([
            'url' => $this->getUrl('category'),
        ]);
    }

    public function quicklyAction(Input $input) {
        if ($input->isAjax()) {
            $this->layout = '';
        }
        $model_list = ModelModel::where('type', 0)->select('name', 'id')->get();
        $group_list = GroupModel::where('type', 0)->get();
        $cat_list = CategoryRepository::all(CMSRepository::context());
        $template_list = $this->getThemeTemplate(true);
        return $this->show('quickly', compact('cat_list',
            'template_list', 'group_list', 'model_list'));
    }

    public function batchSaveAction(Input $input) {
        try {
            CategoryRepository::batchSave(CMSRepository::context(), $input->validate([
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
        $template_list = (new ThemeManager())->loadTemplates(CMSRepository::context()->theme());
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