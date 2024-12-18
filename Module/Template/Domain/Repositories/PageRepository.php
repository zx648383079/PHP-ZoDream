<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Module\Template\Domain\Entities\ThemeStyleEntity;
use Module\Template\Domain\Model\SiteComponentModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Model\SitePageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Module\Template\Domain\VisualEditor\VisualInput;
use Module\Template\Domain\VisualEditor\VisualWeight;
use Zodream\Database\Relation;
use Zodream\Helpers\Arr;
use Zodream\Html\Tree;

final class PageRepository {

    const TYPE_NONE = 0;

    const TYPE_WAP = 1;
    const PUBLISH_STATUS_DRAFT = 0; // 草稿
    const PUBLISH_STATUS_POSTED = 5; // 已发布
    const PUBLISH_STATUS_TRASH = 9; // 垃圾箱

    public static function getList(int $site) {
        if (!SiteRepository::isSelf($site)) {
            throw new \Exception('site is error');
        }
        return SitePageModel::where('site_id', $site)->orderBy('position', 'asc')
            ->orderBy('id', 'asc')->page();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? SitePageModel::query()->where('id', $id)->first() : new SitePageModel();
        if (empty($model)) {
            throw new \Exception(__('id is error'));
        }
        $model->load($data);
        if (!SiteRepository::isSelf(intval($model->site_id))) {
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
        $model = SitePageModel::find($id);
        if (empty($model)) {
            throw new \Exception('page not found');
        }
        if (!SiteRepository::isSelf($model->site_id)) {
            throw new \Exception('page not found');
        }
        $weightId = SitePageWeightModel::where('page_id', $id)->pluck('weight_id');
        SitePageWeightModel::where('page_id', $id)->delete();
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
        $model = SitePageModel::find($id);
        if (empty($model)) {
            throw new \Exception('page not found');
        }
        if (!SiteRepository::isSelf($model->site_id)) {
            throw new \Exception('page not found');
        }
        $data = $model->toArray();
        $data['edit_url'] = url('./@admin/visual', ['id' => $model->id, 'site' => $model->site_id]);
        $data['editable_url'] = url('./@admin/visual/template', ['id' => $model->id, 'site' => $model->site_id]);
        $data['preview_url'] = url('./@admin/visual/preview', ['id' => $model->id, 'site' => $model->site_id]);
        return $data;
    }

    public static function weight(int $id): array {
        $pageModel = SitePageWeightModel::findOrThrow($id);
        $model = SiteWeightModel::findOrThrow($pageModel->weight_id);
        $data = array_merge($model->toArray(), $pageModel->toArray());
        $data['styles'] = ThemeStyleEntity::where('component_id', $model->component_id)->get();
        $data['form'] = [
            'basic' => Arr::toArray(VisualInput::basic($model)),
            'style' => Arr::toArray(VisualInput::style($model)),
        ];
        return $data;
    }

    public static function weightAdd(int $page_id, int $weight_id, int $parent_id,
                                     int $parent_index = 0, int $position = 0, int $group = 0): array {
        $pageModel = SitePageModel::find($page_id);
        $position = self::refreshPosition($parent_id, $parent_index, -1, $position);
        if ($group === 99) {
            $weightModel = SiteWeightModel::where('site_id', $pageModel->site_id)
                ->where('id', $weight_id)
                ->where('is_share', 1)->first();
        } else {
            $weightModel = SiteWeightModel::createOrThrow([
                'component_id' => $weight_id,
                'site_id' => $pageModel->site_id,
            ]);
        }
        if (empty($weightModel)) {
            throw new \Exception('weight is error');
        }
        $model = SitePageWeightModel::createOrThrow([
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
        $items = SitePageWeightModel::query()
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
            SitePageWeightModel::query()->where('id', $item['id'])
                ->update([
                    'position' => $pos
                ]);
        }
        return $currentPos;
    }

    private static function renderWeight(SitePageWeightModel $model): string {
        return (new VisualWeight($model))->render(true);
    }

    public static function weightRefresh(int $id): array {
        $model = SitePageWeightModel::findOrThrow($id);
        $data = $model->toArray();
        $data['html'] = self::renderWeight($model);
        return $data;
    }

    public static function weightMove(int $id, int $parent_id, int $parent_index = 0,
                                      int $position = 0): void {
        $model = SitePageWeightModel::findOrThrow($id);
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

    public static function weightSave(int $id): array {
        $pageModel = SitePageWeightModel::findOrThrow($id);
        // $pageMap = ['parent_id', 'parent_index', 'position'];
        $disable = ['id', 'page_id', 'weight_id', 'component_id',
            'parent_id', 'parent_index', 'position'];
        $maps = ['style_id', 'title', 'content', 'is_share', 'settings'];
        $input = request()->get();
        $data = isset($input['style']) ? $input :
            (new VisualWeight($pageModel))->validateForm($input);
        $model = VisualFactory::cache()->getOrSet(SiteWeightModel::class,
            $pageModel->weight_id, function () use ($pageModel) {
                return SiteWeightModel::where('id', $pageModel->weight_id)
                    ->first();
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

    public static function weightRemove(int $id): bool {
        if ($id < 1) {
            return true;
        }
        $model = SitePageWeightModel::findOrThrow($id);
        $data = [$id];
        $parents = $data;
        while (true) {
            $parents = SitePageWeightModel::whereIn('parent_id', $parents)
                ->pluck('id');
            if (empty($parents)) {
                break;
            }
            $data = array_merge($data, $parents);
        }
        SitePageWeightModel::whereIn('id', $data)->delete();
        SiteWeightModel::whereIn('id', $data)->where('is_share', 0)->delete();
        self::refreshPosition($model->parent_id, $model->parent_index);
        return true;
    }

    public static function weightForm(int $id): array {
        $model = SitePageWeightModel::findOrThrow($id);
        $form = (new VisualWeight($model))->renderForm();
        $data = $model->toArray();
        $data['form'] = Arr::toArray($form);
        return $data;
    }

    /**
     * 组件移动时更新所有部件的位置
     * @param int $id
     * @param array{id:int,position:int,parent_id:int}[] $weights
     * @return void
     */
    public static function batchSave(int $id, array $weights): void {
        $pageModel = SitePageModel::findOrThrow($id);
        if (!SiteRepository::isSelf($pageModel->site_id)) {
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
            SitePageWeightModel::where('page_id', $pageModel->id)
                ->where('id', $weight['id'])
                ->update($data);
        }
    }

    public static function siteId(int $id): int {
        return intval(SitePageModel::where('id', $id)->value('site_id'));
    }
    public static function search(int $siteId): array {
        return SitePageModel::where('site_id', $siteId)->get('id', 'title');
    }

    public static function weightSearch(int $pageId): array {
        $items = SitePageWeightModel::where('page_id', $pageId)->orderBy('parent_id', 'asc')
            ->orderBy('parent_index', 'asc')->get();
        $items = Relation::create($items, [
            Relation::MERGE_RELATION_KEY => Relation::make(SiteWeightModel::select('id', 'title'), 'weight_id', 'id')
        ]);
        return (new Tree($items))->makeIdTree();
    }

    public static function pageSetting(int $pageId): array {
        $theme = Arr::toArray(VisualInput::themeSetting());
        $page = Arr::toArray(VisualInput::pageSetting());
        return compact('theme', 'page');
    }
}