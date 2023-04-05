<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Domain\Repositories\FileRepository;
use Module\Template\Domain\Entities\SiteEntity;
use Module\Template\Domain\Entities\SitePageEntity;
use Module\Template\Domain\Model\SiteComponentModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Model\SitePageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\Model\ThemeComponentModel;
use Module\Template\Domain\Pages\Page;
use Zodream\Database\Contracts\SqlBuilder;

final class SiteRepository extends CRUDRepository {

    protected static function query(): SqlBuilder {
        return SiteModel::query()->where('user_id', auth()->id());
    }

    protected static function createNew(): Model {
        return new SiteModel();
    }

    protected static function removeWith(int $id): bool {
        $ids = SitePageModel::where('site_id', $id)->pluck('id');
        SitePageWeightModel::whereIn('page_id', $ids)->delete();
        SitePageModel::where('site_id', $id)->delete();
        SiteWeightModel::where('site_id', $id)->delete();
        return true;
    }


    public static function weightGroups(int $siteId) {
        $data = SiteComponentModel::with('category')->where('type', 1)->where('site_id', $siteId)->get();
        $weightItems = SiteWeightModel::where('site_id', $siteId)->where('is_share', 1)
            ->get('id', 'component_id', 'title');
        $sourceItems = [];
        $groupItems = [];
        foreach ($data as $item) {
            $temp = $item->toArray();
            $temp['thumb'] = url()->asset(empty($temp['thumb']) ? FileRepository::DEFAULT_IMAGE : $temp['thumb']);
            $temp['id'] = $temp['component_id'];
            if (!isset($groupItems[$item->cat_id])) {
                $groupItems[$item->cat_id] = [
                    'id' => 0,
                    'name' => $item->category->name,
                    'items' => []
                ];
            }
            $groupItems[$item->cat_id]['items'][] = $temp;
            $sourceItems[$temp['id']] = $temp;
        }
        $items = [];
        foreach ($weightItems as $item) {
            if (!isset($sourceItems[$item['component_id']])) {
                continue;
            }
            $temp = $sourceItems[$item['component_id']];
            $items[] = [
                'id' => $item['id'],
                'name' => $item['title'] ?: $temp['name'],
                'description' => $item['title'] ?: $temp['description'],
                'thumb' => $temp['thumb'],
                'editable' => $temp['editable']
            ];
        }
        $groupItems[] = ['id' => 99, 'name' => '共享组件', 'items' => $items];
        return array_values($groupItems);
    }


    public static function getManageList(string $keywords = '', int $user = 0) {
        return SiteModel::with('user')->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['title', 'name'], true, '', $keywords);
        })->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->orderBy('id', 'desc')->page();
    }

    public static function manageGetPage(string $keywords = '', int $site = 0) {
        return SitePageEntity::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['title', 'name'], true, '', $keywords);
        })->when($site > 0, function ($query) use ($site) {
            $query->where('site_id', $site);
        })->orderBy('id', 'desc')->page();
    }

    public static function manageToggle(int $id) {
        $model = SiteEntity::findOrThrow($id);
        $model->status = PageRepository::PUBLISH_STATUS_TRASH;
        $model->save();
        return $model;
    }
    public static function manageTogglePage(int $id) {
        $model = SitePageEntity::findOrThrow($id);
        $model->status = PageRepository::PUBLISH_STATUS_TRASH;
        $model->save();
        return $model;
    }

    public static function manageRemove(int $id) {
        $model = SiteEntity::findOrThrow($id);
        self::removeWith($id);
        $model->delete();
    }
    public static function manageRemovePage(int $id) {
        $model = SitePageEntity::findOrThrow($id);
        SitePageWeightModel::where('page_id', $id)->delete();
        $model->delete();
    }

    public static function selfList(string $keywords = '')
    {
        return SiteModel::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['title', 'name'], true, '', $keywords);
        })->where('user_id', auth()->id())->orderBy('id', 'desc')->page();
    }

    public static function selfSave(array $data)
    {
        $userId = auth()->id();
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? SiteEntity::query()->where('id', $id)
            ->where('user_id', $userId)->first() : new SiteEntity();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        $model->load($data, ['user_id']);
        $model->user_id = $userId;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function selfClone(array $data, int $sourceSite)
    {
        $sourceModel = SiteModel::findOrThrow($sourceSite);
        if ($sourceModel->is_share < 1) {
            throw new \Exception('克隆失败');
        }
        $userId = auth()->id();
        $model = new SiteEntity();
        $model->thumb = $sourceModel->thumb;
        $model->default_page_id = $sourceModel->default_page_id;
        $model->load($data, ['user_id']);
        $model->user_id = $userId;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        $items = SiteComponentModel::where('site_id', $sourceModel->id)->asArray()->get();
        $now = time();
        $replace = [
            'site_id' => $model->id,
            'updated_at' => $now,
            'created_at' => $now,
        ];
        foreach ($items as $item) {
            $clone = [];
            foreach ($item as $k => $v) {
                if ($k === 'id') {
                    continue;
                }
                $clone[$k] = $replace[$k] ?? $v;
            }
            SiteComponentModel::query()->insert($clone);
        }
        $items = SitePageModel::where('site_id', $sourceModel->id)->get();
        $pageMap = [];
        foreach ($items as $item) {
            $clone = [];
            foreach ($item as $k => $v) {
                if ($k === 'id') {
                    continue;
                }
                $clone[$k] = $replace[$k] ?? $v;
            }
            $pageMap[$item['id']] = SitePageModel::query()->insert($clone);
        }
        $items = SiteWeightModel::where('site_id', $sourceModel->id)->get();
        $weightMap = [];
        foreach ($items as $item) {
            $clone = [];
            foreach ($item as $k => $v) {
                if ($k === 'id') {
                    continue;
                }
                $clone[$k] = $replace[$k] ?? $v;
            }
            $weightMap[$item['id']] = SiteWeightModel::query()->insert($clone);
        }
        $items = SitePageWeightModel::where('site_id', $sourceModel->id)->get();
        foreach ($items as $item) {
            $clone = [];
            foreach ($item as $k => $v) {
                if ($k === 'id') {
                    continue;
                }
                $clone[$k] = $v;
            }
            $clone['page_id'] = $pageMap[$item['page_id']];
            $clone['weight_id'] = $weightMap[$item['weight_id']];
            $clone['site_id'] = $replace['site_id'];
            SitePageWeightModel::query()->insert($clone);
        }
        if ($model->default_page_id > 0) {
            $model->default_page_id = $pageMap[$model->default_page_id];
            $model->save();
        }
        return $model;
    }

    public static function selfRemove(int $id)
    {
        $model = SiteEntity::findWithAuth($id);
        if (empty($model)) {
            return;
        }
        SiteComponentModel::where('site_id', $id)->delete();
        SitePageWeightModel::where('site_id', $id)->delete();
        SitePageModel::where('site_id', $id)->delete();
        SiteWeightModel::where('site_id', $id)->delete();
        $model->delete();
    }

    public static function selfGetPage(int $site, string $keywords = '')
    {
        $siteModel = SiteModel::findWithAuth($site);
        if (empty($siteModel)) {
            return new Page();
        }
        $page = SitePageModel::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['title', 'name'], true, '', $keywords);
        })->where('site_id', $site)->orderBy('id', 'desc')->page();
        foreach ($page as $item) {
            $item->is_default = $item->id === $siteModel->default_page_id;
        }
        return $page;
    }

    public static function selfSavePage(array $data)
    {
        $id = $data['id'] ?? 0;
        $site = $data['site_id'] ?? 0;
        unset($data['id']);
        if (!self::isSelf(intval($site))) {
            throw new \Exception('数据有误');
        }
        $model = $id > 0 ? SitePageModel::query()->where('id', $id)
            ->where('site_id', $site)->first() : new SitePageModel();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        $model->load($data);
        $model->site_id = $site;
        if (isset($data['site_component_id']) && $data['site_component_id'] > 0) {
            $model->component_id = SiteComponentModel::where('site_id', $site)
                ->where('id', intval($data['site_component_id']))->value('component_id');
        }
        if ($model->component_id < 1) {
            throw new \Exception('Component is error');
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (isset($data['is_default']) && $data['is_default']) {
            SiteModel::where('site_id', $model->site_id)->update([
               'default_page_id' => $model->id
            ]);
        }
        return $model;
    }

    public static function selfRemovePage(int $id)
    {
        $model = SitePageModel::find($id);
        if (empty($model) || !self::isSelf($model->site_id)) {
            throw new \Exception('数据有误');
        }
        SitePageWeightModel::where('page_id', $id)->delete();
        $model->delete();
    }

    public static function selfGetComponent(int $site, string $keywords = '', int $type = 0)
    {
        if (!self::isSelf($site)) {
            return new Page();
        }
        return SiteComponentModel::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], true, '', $keywords);
        })->where('type', $type)->where('site_id', $site)->orderBy('id', 'desc')->page();
    }

    public static function selfAddComponent(array|int $site, array|int $id)
    {

        $success = 0;
        foreach ((array)$id as $itemId) {
            $model = ThemeComponentModel::findOrThrow($itemId);
            foreach ((array)$site as $siteId) {
                $siteId = intval($siteId);
                if (!self::isSelf($siteId)) {
                    throw new \Exception('数据有误');
                }
                if (SiteComponentModel::where('site_id', $siteId)
                        ->where('component_id', $itemId)->count() > 0) {
                    continue;
                }
                SiteComponentModel::createOrThrow([
                    'component_id' => $itemId,
                    'site_id' => $siteId,
                    'cat_id' => $model->cat_id,
                    'name' => $model->name,
                    'description' => $model->description,
                    'thumb' => $model->thumb,
                    'type' => $model->type,
                    'author' => $model->author,
                    'version' => $model->version,
                    'path' => $model->path,
                    'editable' => $model->editable,
                    'alias_name' => $model->alias_name,
                    'dependencies' => $model->dependencies
                ]);
            }
            $success ++;
        }
        if ($success < 1) {
            throw new \Exception('添加失败');
        }
    }

    public static function selfRemoveComponent(int $id)
    {
        $model = SiteComponentModel::find($id);
        if (empty($model) || !self::isSelf($model->site_id)) {
            throw new \Exception('数据有误');
        }
        $model->delete();
        $ids = SiteWeightModel::where('component_id', $model->component_id)->where('site_id', $model->site_id)
            ->pluck('id');
        SiteWeightModel::where('component_id', $model->component_id)->where('site_id', $model->site_id)
            ->delete();
        SitePageWeightModel::whereIn('weight_id', $ids)->delete();
    }

    public static function isSelf(int $site): bool {
        return SiteModel::where('user_id', auth()->id())
            ->where('id', $site)->count() > 0;
    }

    public static function getShareList(string $keywords = '', int $user = 0,
                                        string $sort = 'created_at',
                                        string|int|bool $order = 'desc') {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['id', 'share_price', 'created_at']);
        return SiteModel::with('user')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name', 'title'], true, '', $keywords);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->where('is_share', 1)->orderBy($sort, $order)->page();
    }
}