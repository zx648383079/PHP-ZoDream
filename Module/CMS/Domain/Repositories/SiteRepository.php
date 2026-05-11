<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\ThemeManager;
use Zodream\Http\Uri;

class SiteRepository {

    const TYPE_CATEGORY = 0;
    const TYPE_ARTICLE = 1;
    const TYPE_COMMENT = 2;
    const TYPE_MODEL = 3;
    const TYPE_MODEL_FIELD = 4;
    const TYPE_LINKAGE = 5;
    const TYPE_LINKAGE_DATA = 6;
    const TYPE_GROUP = 7;
    const TYPE_SITE = 8;

    const ACTION_AGREE = 1;
    const ACTION_DISAGREE = 2;

    const PUBLISH_STATUS_DRAFT = 0; // 草稿
    const PUBLISH_STATUS_POSTED = 5; // 已发布
    const PUBLISH_STATUS_TRASH = 9; // 垃圾箱

    public static function getList(string $keywords = '') {
        return SiteModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->orderBy('id', 'asc')->page();
    }

    public static function get(int $id) {
        return SiteModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = intval($data['id'] ?? 0);
        unset($data['id']);
        $model = SiteModel::findOrNew($id);
        $oldTheme = $model->theme;
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        CacheRepository::onSiteUpdated(intval($model->id));
        if (!empty($oldTheme) && $oldTheme !== $model->theme) {
            $old = new SiteModel();
            $old->id = $model->id;
            $old->theme = $oldTheme;
            CMSRepository::removeSite($model);
        }
        if ($oldTheme === $model->theme) {
            return $model;
        }
        // 根据主题和语言包自动分组
        if ($id === 0) {
            static::autoBindingLocalize($model);
        }
        try {
            CMSRepository::generateSite($model);
        } catch (\Exception $ex) {
            static::remove($model->id);
            throw $ex;
        }
        return $model;
    }

    private static function autoBindingLocalize(SiteModel $model): bool {
        if (!$model->language) {
            return false;
        }
        $items = SiteModel::where('theme', $model->theme)
            ->where('id', '<>', $model->id)
            ->whereNotNull('language')->orderBy('id', 'asc')
            ->get('id', 'language', 'locale_group_id');
        if (count($items) < 1) {
            return false;
        }
        $locale_group_id = intval($items[0]['locale_group_id']);
        if ($locale_group_id === 0) {
            $locale_group_id = intval($items[0]['id']);
        }
        $model->locale_group_id = $locale_group_id;
        $idItems = array_map(function ($item) {
            return intval($item['id']);
        }, $items);
        $idItems[] = $model->id;
        SiteModel::query()->whereIn('id', $idItems)->update(compact('locale_group_id'));
        return true;
    }

    public static function localeItems(SiteModel|null $model): array {
        if (empty($model)) {
            $model = CMSRepository::site();
        }
        $locale_group_id = intval($model->locale_group_id);
        if ($locale_group_id === 0) {
            return [];
        }
        $items = SiteModel::where('locale_group_id', $locale_group_id)
            ->asArray()
            ->get('id', 'language', 'title');
        if ($items < 2) {
            return [];
        }
        return array_map(function ($item) use($model) {
            return [
                'selected' => intval($model->id) === intval($item['id']) ,
                'language' => $item['language'],
                'id' => intval($item['id']),
                'name' => $item['title']
            ];
        }, $items);
    }

    public static function remove(int $id) {
        $model = static::get($id);
        $model->delete();
        CMSRepository::removeSite($model);
        $locale_group_id = intval($model->locale_group_id);
        if ($locale_group_id === 0) {
            return;
        }
        $count = SiteModel::where('locale_group_id', $locale_group_id)
            ->count();
        if ($count === 1) {
            SiteModel::where('locale_group_id', $locale_group_id)->update([
                'locale_group_id' => 0
            ]);
        }
    }

    public static function setDefault(int $id) {
        $model = static::get($id);
        $model->is_default = 1;
        $model->save();
        SiteModel::where('id', '<>', $id)->update([
            'is_default' => 0
        ]);
    }

    public static function option(int $id) {
        $model = static::get($id);
        return $model->options;
    }

    public static function optionSave(int $id, array $data) {
        $model = static::get($id);
        $model->options = $data;
        $model->save();
    }

    public static function apply(int $id) {
        if (CMSRepository::siteId() === $id) {
            return;
        }
        CMSRepository::site(new SiteModel(compact('id')));
    }

    public static function themeList() {
        return (new ThemeManager)->loadThemes();
    }

    /**
     * 遍历全部站点
     * @param callable $cb
     * @return void
     * @throws \Exception
     */
    public static function mapTemporary(callable $cb): void {
        $items = SiteModel::query()->get();
        $source = CMSRepository::site();
        $scene = CMSRepository::scene();
        foreach ($items as $item) {
            CMSRepository::site($item);
            call_user_func($cb, $scene, $item);
        }
        CMSRepository::site($source);
    }

    public static function formatStatus(mixed $status): string {
        return match (intval($status)) {
            static::PUBLISH_STATUS_DRAFT => '草稿',
            static::PUBLISH_STATUS_POSTED => '已发布',
            static::PUBLISH_STATUS_TRASH => '已删除',
            default => '--',
        };
    }

    public static function getAll(): array {
        if (auth()->guest()) {
            return CacheRepository::getSiteCache();
        }
        $user = auth()->user();
        if (!$user->isAdministrator() && !$user->hasRole(CMSRepository::MANAGE_ROLE)) {
            return CacheRepository::getSiteCache();
        }
        $data = SiteModel::query()->asArray()
            ->orderBy('id', 'asc')
            ->get('id', 'is_default', 'match_rule');
        return array_map(function ($item) {
            return [
                'id' => intval($item['id']),
                'is_default' => $item['is_default'] > 0,
                // 'match_type' => intval($item['match_type']),
                'match_rule' => empty($item['match_rule']) ? '' : ltrim($item['match_rule'], '/')
            ];
        }, $data);
    }

    public static function encodeUrl(mixed $siteModel, string $path, array $data = []): string {
        $uri = new Uri($siteModel['match_rule']);
        $request = request();
        if (empty($uri->getScheme())) {
            $uri->setScheme($request->scheme());
        }
        if (empty($uri->getHost())) {
            $uri->setHost($request->host());
        }
        if (str_starts_with($path, './')) {
            $path = substr($path, 2);
        }
        if (!empty($path)) {
            $uri->addPath($path);
        }
        $uri->addData($data);
        return (string)url()->encode($uri);
    }
}