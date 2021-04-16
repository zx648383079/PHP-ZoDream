<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Zodream\Disk\File;
use Zodream\Html\Rss\Rss;
use Zodream\Html\Rss\RssItem;

class RssRepository {
    const CACHE_KEY = '__blog_rss__';

    public static function render(): string {
        $rss = new Rss();
        $rss->setTitle(__('site title'))
            ->setDescription(__('site description'))
            ->setLink(url('/'))
            ->setImage(url()->asset('assets/images/favicon.png'), url('/'));
        $model_list = BlogModel::query()->with('term')
            ->where('open_type', BlogModel::OPEN_PUBLIC)
            ->orderBy('id', 'desc')
            ->get('id', 'term_id', 'title', 'edit_type', 'content', 'programming_language', 'created_at');
        $metaItems = static::getMeta();
        foreach ($model_list as $item) {
            /** @var BlogModel $item */
            $rssItem = new RssItem();
            $rssItem->setTitle($item->title)
                ->setLink(url('blog', ['id' => $item->id]))
                ->setPubDate($item->getAttributeSource('created_at'))
                ->setDescription(BlogRepository::renderLazyContent($item, false))
                ->addTag('category', $item->term->name);
            if (isset($metaItems[$item->id]['video_url'])) {
                $file = static::toFile($metaItems[$item->id]['video_url']);
                if ($file) {
                    $rssItem->enclosure($metaItems[$item->id]['video_url'],
                        'video/'. $file->getExtension(), $file->size());
                }
            }
            if (isset($metaItems[$item->id]['audio_url'])) {
                $file = static::toFile($metaItems[$item->id]['audio_url']);
                if ($file) {
                    $rssItem->enclosure($metaItems[$item->id]['audio_url'],
                        'audio/'. $file->getExtension(), $file->size());
                }
            }
            $rss->addItem($rssItem);
        }
        return (string)$rss;
    }

    /**
     * @param string $url
     * @return \Zodream\Disk\File|null
     * @throws \Exception
     */
    protected static function toFile(string $url) {
        if (str_contains($url, '//')) {
            $url = parse_url($url, PHP_URL_PATH);
        }
        $file = public_path($url);
        return $file->exist() ? $file : null;
    }

    protected static function getMeta() {
        $data = BlogMetaModel::query()->whereIn('name', ['audio_url', 'video_url'])
            ->asArray()
            ->get('blog_id', 'name', 'content');
        $items = [];
        foreach ($data as $item) {
            if (empty($item['content'])) {
                continue;
            }
            if (!isset($items[$item['blog_id']])) {
                $items[$item['blog_id']] = [];
            }
            $items[$item['blog_id']][$item['name']] = $item['content'];
        }
        return $items;
    }

    public static function renderOrCache() {
        if (app()->isDebug()) {
            return static::render();
        }
        return cache()->getOrSet(static::CACHE_KEY, function () {
            return static::render();
        });
    }
}