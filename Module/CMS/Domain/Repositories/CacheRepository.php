<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\TreeObject;
use Module\SEO\Domain\Option;
use Zodream\Helpers\Str;

final class CacheRepository {

    const CACHE_PAGE = [
        ['name' => '全部页面缓存', 'value' => 'page'],
        ['name' => '首页缓存', 'value' => 'home_page'],
        ['name' => '栏目页缓存', 'value' => 'channel_page'],
        ['name' => '内容页缓存', 'value' => 'content_page'],
    ];
    const CACHE_DATA = [
        ['name' => '全部数据缓存', 'value' => 'data'],
        ['name' => '联动项缓存', 'value' => 'linkage'],
        ['name' => '模型缓存', 'value' => 'model'],
        ['name' => '栏目缓存', 'value' => 'channel'],
        ['name' => '文章缓存', 'value' => 'article'],
        ['name' => '站点配置缓存', 'value' => 'option'],
    ];

    public static function storeItems(): array {
        return array_merge(self::CACHE_PAGE, self::CACHE_DATA);
    }

    public static function clearCache(array $store = []): void {
        self::flushCache(empty($store) ? array_column(self::storeItems(), 'value') : $store);
    }

    public static function clearExcludeCache(array $exclude = []): void {
        $items = [];
        foreach (self::storeItems() as $item) {
            if (in_array($item['value'], $exclude)) {
                continue;
            }
            $items[] = $item['value'];
        }
        self::flushCache($items);
    }

    protected static function flushCache(array $storeItems): void {
        if (empty($storeItems)) {
            return;
        }
        foreach ($storeItems as $item) {
            if (empty($item)) {
                continue;
            }
            if ($item === 'default') {
                cache()->flush();
                continue;
            }
            $method = sprintf('%s::flush%sCache', self::class, Str::studly($item));
            if (is_callable($method)) {
                try {
                    call_user_func($method);
                } catch (\Exception){};
            }
        }
    }

    public static function linkageKey(int $id) {
        return 'cms_linkage_tree_'.$id;
    }

    public static function channelKey(int $site) {
        return 'cms_channel_tree_'.$site;
    }

    public static function modelKey(int $id) {
        return 'cms_model_'.$id;
    }

    public static function optionKey(int $site) {
        return 'cms_option_'.$site;
    }

    public static function mapKey(int $site) {
        return 'cms_map_'.$site;
    }

    public static function seoKey(int $site) {
        return 'cms_seo_'.$site;
    }

    public static function siteKey() {
        return 'cms_site_rule';
    }

    public static function getLinkageCache(int $id): TreeObject {
        if ($id < 1) {
            return new TreeObject();
        }
        return cache()->getOrSet(self::linkageKey($id), function () use ($id) {
            return new TreeObject(LinkageDataModel::query()->where('linkage_id', $id)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')
                ->get());
        }, 0);
    }

    public static function getChannelCache(): TreeObject {
        $site = CMSRepository::siteId();
        return cache()->getOrSet(self::channelKey($site), function () {
            return new TreeObject(CategoryModel::query()
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')
                ->get());
        });
    }

    /**
     * @param int $id
     * @return array{id: numeric, table: string, field_items: array}
     * @throws \Exception
     */
    public static function getModelCache(int $id): array {
        if ($id < 1) {
            return [];
        }
        return cache()->getOrSet(self::modelKey($id), function () use ($id) {
            $model = ModelModel::query()->where('id', $id)->first();
            if (empty($model)) {
                return [];
            }
            $data = $model->toArray();
            $fieldItems = ModelFieldModel::where('model_id', $id)
                ->orderBy('position', 'asc')
                ->orderBy('is_system', 'desc')
                ->orderBy('id', 'asc')
                ->get();
            $data['field_items'] = [];
            foreach ($fieldItems as $item) {
                $data['field_items'][] = $item->toArray();
            }
            return $data;
        });
    }

    public static function getOptionCache(): array {
        $site = CMSRepository::siteId();
        return cache()->getOrSet(self::optionKey($site), function () {
            $site = CMSRepository::site();
            $items = [];
            foreach ($site['options'] as $item) {
                $items[$item['code']] = Option::formatOption((string)($item['value'] ?? ''), $item['type']);
            }
            foreach (
                ['title', 'keywords', 'description', 'logo', 'language'] as $code
            ) {
                $items[$code] = $code === 'logo' ? url()->asset($site[$code]) : $site[$code];
            }
            return $items;
        });
    }

    /**
     * 获取栏目，模型，联动 id name 映射表
     * @return array
     */
    public static function getMapCache(): array {
        $site = CMSRepository::siteId();
        return cache()->getOrSet(self::mapKey($site), function () {
            $lang = CMSRepository::site()->language;
            $model = ModelModel::query()->selectRaw('id,`table` as name')
                ->pluck('id', 'name');
            $linkage = [];
            $items = LinkageModel::query()->get( 'id', 'code', 'language');
            foreach ($items as $item) {
                if (isset($linkage[$item['code']]) && $lang !== $item['language']) {
                    continue;
                }
                $linkage[$item['code']] = $item['id'];
            }
            $channel = CategoryModel::query()->pluck( 'id', 'name');
            return compact('model', 'linkage', 'channel');
        });
    }

    public static function getSeoCache(): array {
        $site = CMSRepository::siteId();
        return cache()->getOrSet(self::seoKey($site), function () {
            return ContentModel::query()
                ->where('seo_link', '!=', '')
                ->where('status', SiteRepository::PUBLISH_STATUS_POSTED)
                ->select('id', 'model_id', 'cat_id', 'seo_link')->asArray()->get();
        });
    }

    /**
     * 获取站点的匹配规则
     * @return array{id: int, is_default: bool, match_type: int, match_rule: string}[]
     */
    public static function getSiteCache(): array {
        return cache()->getOrSet(self::siteKey(), function () {
            $data = SiteModel::where('status', SiteRepository::PUBLISH_STATUS_POSTED)
                ->asArray()
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
        });
    }

    public static function flushDataCache() {
        try {
            $site = CMSRepository::siteId();
            cache()->delete(self::mapKey($site));
        } catch (\Exception) {};
        cache()->delete(self::siteKey());
    }

    public static function flushLinkageCache() {
        $items = LinkageModel::query()->pluck('id');
        foreach ($items as $id) {
            cache()->delete(self::linkageKey(intval($id)));
        }
    }

    public static function flushChannelCache() {
        cache()->delete(self::channelKey(CMSRepository::siteId()));
    }

    public static function flushModelCache() {
        $items = ModelModel::query()->pluck('id');
        foreach ($items as $id) {
            cache()->delete(self::modelKey(intval($id)));
        }
    }

    public static function flushArticleCache() {
        cache()->delete(self::seoKey(CMSRepository::siteId()));
    }

    public static function flushOptionCache() {
        cache()->delete(self::optionKey(CMSRepository::siteId()));
    }

    public static function onSiteUpdated(int $id): void {
        cache()->delete(self::optionKey($id));
        cache()->delete(self::siteKey());
    }

    public static function onLinkageUpdated(int $id): void {
        cache()->delete(self::linkageKey($id));
    }

    public static function onChannelUpdated(int $id): void {
        self::flushChannelCache();
    }

    public static function onModelUpdated(int $id): void {
        cache()->delete(self::modelKey($id));
    }
}