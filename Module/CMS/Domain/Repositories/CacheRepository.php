<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Helpers\Str;

final class CacheRepository {

    const CACHE_PAGE = [
        ['name' => '首页缓存', 'value' => 'home'],
        ['name' => '栏目页缓存', 'value' => 'channel'],
        ['name' => '内容页缓存', 'value' => 'content'],
    ];
    const CACHE_DATA = [
        ['name' => '联动项缓存', 'value' => 'linkage'],
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
                call_user_func($method);
            }
        }
    }

    public static function flushLinkageCache() {
        $items = LinkageModel::query()->pluck('id');
        foreach ($items as $id) {
            cache()->delete('cms_linkage_tree_'.$id);
        }
    }
}