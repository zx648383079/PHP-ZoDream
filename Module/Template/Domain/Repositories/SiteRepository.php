<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Module\Template\Domain\Entities\SiteEntity;
use Module\Template\Domain\Entities\SitePageEntity;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
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

    public static function isUser(int $site): bool {
        return SiteModel::where('user_id', auth()->id())->where('id', $site)->count() > 0;
    }

    public static function weightGroups(int $themeId, int $siteId) {
        $data = ThemeRepository::weightGroups($themeId);
        $weightItems = SiteWeightModel::where('site_id', $siteId)->where('is_share', 1)
            ->get('id', 'theme_weight_id', 'title');
        $sourceItems = [];
        foreach ($data as $i => $group) {
            foreach ($group['items'] as $j => $item) {
                $temp = $item->toArray();
                $data[$i]['items'][$j] = $temp;
                $sourceItems[$temp['id']] = $temp;
            }
        }
        $items = [];
        foreach ($weightItems as $item) {
            if (!isset($sourceItems[$item['theme_weight_id']])) {
                continue;
            }
            $temp = $sourceItems[$item['theme_weight_id']];
            $items[] = [
                'id' => $item['id'],
                'name' => $item['title'] ?: $temp['name'],
                'description' => $item['title'] ?: $temp['description'],
                'thumb' => $temp['thumb'],
                'editable' => $temp['editable']
            ];
        }
        $data[] = ['id' => 99, 'name' => '共享组件', 'items' => $items];
        return $data;
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

    public static function selfRemove(int $id)
    {
        $model = SiteEntity::findWithAuth($id);
        if (empty($model)) {
            return;
        }
        SitePageWeightModel::where('site_id', $id)->delete();
        SitePageModel::where('site_id', $id)->delete();
        $model->delete();
    }

    public static function selfGetPage(int $site, string $keywords = '')
    {
        if (!self::isSelf($site)) {
            return new Page();
        }
        return SitePageModel::when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['title', 'name'], true, '', $keywords);
        })->where('site_id', $site)->orderBy('id', 'desc')->page();
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
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
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

    public static function selfAddComponent(int $site, array|int $id)
    {
        if (!self::isSelf($site)) {
            throw new \Exception('数据有误');
        }
        $success = 0;
        foreach ((array)$id as $item) {
            if (SiteComponentModel::where('site_id', $site)
                    ->where('component_id', $item)->count() > 0) {
                continue;
            }
            $model = ThemeComponentModel::findOrThrow($id);
            SiteComponentModel::createOrThrow([
                'component_id' => $id,
                'site_id' => $site,
                'name' => $model->name,
                'description' => $model->description,
                'thumb' => $model->thumb,
                'type' => $model->type,
                'author' => $model->author,
                'version' => $model->version,
                'path' => $model->path,
            ]);
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
}