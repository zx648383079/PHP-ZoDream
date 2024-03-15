<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Helpers;

use Module\Blog\Domain\Middleware\BlogSeoMiddleware;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\PublishRepository;
use Module\SEO\Domain\SiteMap;

class RouterHelper {

    const CACHE_KEY = 'blog_seo_link';

    private static function getMap(bool $isIdKey): array {
        $data = cache()->getOrSet(static::CACHE_KEY, function () {
            return BlogMetaModel::where('name', 'seo_link')
                ->whereNotNull('content')
                ->orderBy('id', 'asc')
                ->asArray()
                ->get('content', 'blog_id');
        });
        return $isIdKey ? array_column($data, 'content', 'blog_id') :
            array_column($data, 'blog_id', 'content');
    }

    public static function linkId(string $path): int {
        if (empty($path)) {
            return 0;
        }
        $data = static::getMap(false);
        if (empty($data)) {
            return 0;
        }
        if (!isset($data[$path])) {
            return 0;
        }
        return intval($data[$path]);
    }

    public static function idLink(string|int $id): string {
        $data = static::getMap(true);
        if (empty($data)) {
            return '';
        }
        if (!isset($data[$id])) {
            return '';
        }
        return $data[$id];
    }

    public static function reset(): void {
        cache()->delete(static::CACHE_KEY);
    }

    public static function openLinks(SiteMap $map): void {
        $map->add(url('./'), time());
        $map->add(url('./tag'), time());
        $map->add(url('./category'), time());
        $map->add(url('./archives'), time());
        $items = BlogModel::where('publish_status', PublishRepository::PUBLISH_STATUS_POSTED)
            ->orderBy('id', 'desc')
            ->get('id', 'language', 'updated_at');
        foreach ($items as $item) {
            $map->add(BlogSeoMiddleware::encodeUrl($item['id'], $item['language']),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
        $items = TermModel::orderBy('id', 'desc')
            ->get('id');
        foreach ($items as $item) {
            $map->add(url('./', ['category' => $item->id]),
                time(), SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
        $items = TagModel::orderBy('id', 'desc')
            ->get('name');
        foreach ($items as $item) {
            $map->add(url('./', ['tag' => $item->name]),
                time(), SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
    }
}