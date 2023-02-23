<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\Model\ThemeWeightModel;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Module\Template\Domain\VisualEditor\VisualWeight;

final class PageRepository {

    const TYPE_NONE = 0;

    const TYPE_WAP = 1;
    const PUBLISH_STATUS_DRAFT = 0; // 草稿
    const PUBLISH_STATUS_POSTED = 5; // 已发布
    const PUBLISH_STATUS_TRASH = 9; // 垃圾箱

    public static function getList(int $site) {
        if (!SiteRepository::isUser($site)) {
            throw new \Exception('site is error');
        }
        return PageModel::where('site_id', $site)->orderBy('position', 'asc')
            ->orderBy('id', 'asc')->page();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? PageModel::query()->where('id', $id)->first() : new PageModel();
        if (empty($model)) {
            throw new \Exception(__('id is error'));
        }
        $model->load($data);
        if (!SiteRepository::isUser($model->site_id)) {
            throw new \Exception(__('page is error'));
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (isset($data['is_default']) && $data['is_default'] > 0) {
            SiteModel::where('id', $model->site_id)->update([
                'default_page_id' => $model->id
            ]);
        }
        return $model;
    }

    /**
     * 删除
     * @param int $id
     * @return int 返回站点的id
     * @throws \Exception
     */
    public static function remove(int $id): int {
        $model = PageModel::find($id);
        if (empty($model)) {
            throw new \Exception('page not found');
        }
        if (!SiteRepository::isUser($model->site_id)) {
            throw new \Exception('page not found');
        }
        $weightId = PageWeightModel::where('page_id', $id)->pluck('weight_id');
        PageWeightModel::where('page_id', $id)->delete();
        if (!empty($weightId)) {
            SiteWeightModel::where('site_id', $model->site_id)
                ->whereIn('id', $weightId)
                ->where('is_share', 0)->delete();
        }
        $model->delete();
        SiteModel::where('id', $model->site_id)->where('default_page_id', $model->id)->update([
            'default_page_id' => 0
        ]);
        return $model->site_id;
    }

    public static function getInfo(int $id): array {
        $model = PageModel::find($id);
        if (empty($model)) {
            throw new \Exception('page not found');
        }
        if (!SiteRepository::isUser($model->site_id)) {
            throw new \Exception('page not found');
        }
        $data = $model->toArray();
        $data['edit_url'] = url('./@admin/page/template', ['id' => $model->id, 'edit' => true]);
        $data['preview_url'] = url('./page', ['name' => $model->name]);
        return $data;
    }

    public static function weight(int $id) {
        $pageModel = PageWeightModel::findOrThrow($id);
        $model = SiteWeightModel::findOrThrow($pageModel->weight_id);
        return array_merge($model->toArray(), $pageModel->toArray());
    }

    public static function weightAdd(int $page_id, int $weight_id, int $parent_id,
                                     int $parent_index = 0, int $position = 0, int $group = 0): array {
        $pageModel = PageModel::find($page_id);
        $position = self::refreshPosition($parent_id, $parent_index, -1, $position);
        if ($group === 99) {
            $weightModel = SiteWeightModel::where('site_id', $pageModel->site_id)
                ->where('id', $weight_id)
                ->where('is_share', 1)->first();
        } else {
            $weightModel = SiteWeightModel::createOrThrow([
                'theme_weight_id' => $weight_id,
                'site_id' => $pageModel->site_id,
            ]);
        }
        if (empty($weightModel)) {
            throw new \Exception('weight is error');
        }
        $model = PageWeightModel::createOrThrow([
            'page_id' => $pageModel->id,
            'weight_id' => $weightModel->id,
            'parent_id' => $parent_id,
            'parent_index' => $parent_index,
            'site_id' => $pageModel->site_id,
            'position' => $position
        ]);
        $data = $pageModel->toArray();
        $data['html'] = self::renderWeight($model);
        return $data;
    }

    /**
     * 重排顺序
     * @param int $parent_id
     * @param int $parent_index
     * @param int $currenId 为0 表示不插入此队列
     * @param int $currentPos
     * @return int
     */
    private static function refreshPosition(int $parent_id, int $parent_index,
                                            int $currenId = 0, int $currentPos = 0): int {
        if ($currenId === 0) {
            $currentPos = 0;
        }
        // 重排一下位置
        $items = PageWeightModel::query()
            ->where('parent_id', $parent_id)
            ->where('parent_index', $parent_index)
            ->where('id', '<>', $currenId)->orderBy('position', 'asc')
            ->asArray()->get('id', 'position');
        $maxPos = count($items) + 1;
        if ($currentPos < 1 || $currentPos > $maxPos) {
            $currentPos = $maxPos;
        }
        foreach ($items as $i => $item) {
            $pos = $i + 1;
            if ($pos >= $currentPos) {
                $pos ++;
            }
            if ($pos === intval($item['position'])) {
                continue;
            }
            PageWeightModel::query()->where('id', $item['id'])
                ->update([
                    'position' => $pos
                ]);
        }
        return $currentPos;
    }

    private static function renderWeight(PageWeightModel $model): string {
        return (new VisualWeight($model))->render(true);
    }

    public static function weightRefresh(int $id) {
        $model = PageWeightModel::findOrThrow($id);
        $data = $model->toArray();
        $data['html'] = self::renderWeight($model);
        return $data;
    }

    public static function weightMove(int $id, int $parent_id, int $parent_index = 0, int $position = 0) {
        $model = PageWeightModel::findOrThrow($id);
        if ($model->parent_id === $parent_id
            && $model->parent_index === $parent_index) {
            if ($model->position === $position) {
                return;
            }
            $position = self::refreshPosition($parent_id, $parent_index, $id, $position);
        } else {
            self::refreshPosition($model->parent_id, $model->parent_index, $model->id, 0);
            $position = self::refreshPosition($parent_id, $parent_index, $id, $position);
        }
        $model->position = $position;
        $model->parent_id = $parent_id;
        $model->parent_index = $parent_index;
        $model->save();
    }

    public static function weightSave(int $id) {
        $pageModel = PageWeightModel::findOrThrow($id);
        // $pageMap = ['parent_id', 'parent_index', 'position'];
        $disable = ['id', 'page_id', 'weight_id', 'theme_weight_id',
            'parent_id', 'parent_index', 'position'];
        $maps = ['theme_style_id', 'title', 'content', 'is_share', 'settings'];
        $data = (new VisualWeight($pageModel))->parseForm();
        $model = VisualFactory::getOrSet(ThemeWeightModel::class,
            $pageModel->weight_id, function () use ($pageModel) {
                return SiteWeightModel::where('id', $pageModel->weight_id);
            });
        $args = [
            'settings' => $model->getSettingsAttribute()
        ];
        foreach ($data as $key => $value) {
            if (in_array($key, $disable)) {
                continue;
            }
            if ($key === 'settings') {
                $args['settings'] = array_merge($args['settings'], $value);
                continue;
            }
            if (in_array($key, $maps)) {
                $args[$key] = $value;
                continue;
            }
            $args['settings'][$key] = $value;
        }
        $model->set($args);
        $model->save();
        $data = $pageModel->toArray();
        $data['html'] = self::renderWeight($pageModel);
        return $data;
    }

    public static function weightRemove(int $id) {
        if ($id < 1) {
            return true;
        }
        $model = PageWeightModel::findOrThrow($id);
        $data = [$id];
        $parents = $data;
        while (true) {
            $parents = PageWeightModel::whereIn('parent_id', $parents)
                ->pluck('id');
            if (empty($parents)) {
                break;
            }
            $data = array_merge($data, $parents);
        }
        PageWeightModel::whereIn('id', $data)->delete();
        SiteWeightModel::whereIn('id', $data)->where('is_share', 0)->delete();
        self::refreshPosition($model->parent_id, $model->parent_index);
        return true;
    }

    public static function weightForm(int $id) {
        $model = PageWeightModel::find($id);
        $html = (new VisualWeight($model))->renderForm();
        $data = $model->toArray();
        $data['html'] = $html;
        return $data;
    }

    /**
     * 组件移动时更新所有部件的位置
     * @param int $id
     * @param array{id:int,position:int,parent_id:int}[] $weights
     * @return void
     */
    public static function batchSave(int $id, array $weights) {
        $pageModel = PageModel::findOrThrow($id);
        if (!SiteRepository::isUser($pageModel->site_id)) {
            throw new \Exception('page is error');
        }
        $maps = ['position', 'parent_id', 'parent_index'];
        foreach ($weights as $weight) {
            if (isset($weight['id'])) {
                continue;
            }
            $data = [];
            foreach ($maps as $key) {
                if (isset($weight[$key])) {
                    $data[$key] = intval($weight[$key]);
                }
            }
            if (empty($data)) {
                continue;
            }
            PageWeightModel::where('page_id', $pageModel->id)
                ->where('id', $weight['id'])
                ->update($data);
        }
    }
}