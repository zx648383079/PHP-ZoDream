<?php
namespace Module\Disk\Domain\Repositories;

use Module\Disk\Domain\Adapters\BaseDiskAdapter;
use Module\Disk\Domain\Adapters\Database;
use Module\Disk\Domain\Adapters\IDiskAdapter;
use Module\Disk\Domain\FFmpeg;
use Zodream\Http\Uri;

class DiskRepository {

    private static $driver;

    /**
     * @return IDiskAdapter|BaseDiskAdapter
     */
    public static function driver() {
        if (!empty(static::$driver)) {
            return static::$driver;
        }
        $configs = config('disk', [
            'driver' => Database::class,
            'cache' => 'data/disk/cache/',
            'disk' => 'data/disk/file/'
        ]);
        if (isset($configs['ffmpeg'])) {
            FFmpeg::$driver = $configs['ffmpeg'];
        }
        $driver = $configs['driver'];
        return static::$driver = new $driver($configs);
    }

    /**
     * 对网址进行许可
     * @param array|string $items
     * @return array|string
     */
    public static function allowUrl(array|string $items) {
        static $token = '';
        if (empty($items)) {
            return $items;
        }
        if (auth()->guest()) {
            return $items;
        }
        if (empty($token)) {
            $token = md5(sprintf('%s-%s-%s', is_array($items) ? implode('-', $items)
                : $items, time(), auth()->id()));
            cache()->store('disk')->set($token, auth()->id(), 3600);
        }
        $cb = function ($url) use ($token) {
            if (empty($url)) {
                return '';
            }
            $uri = new Uri($url);
            $uri->addData('token', $token);
            return (string)$uri;
        };
        if (!is_array($items)) {
            return $cb($items);
        }
        return array_map($cb, $items);
    }

    public static function file(string $id): array {
        $disk = DiskRepository::driver()->file($id);
        foreach ([
                     'thumb', 'url', 'subtitles', 'lyrics'
                 ] as $key) {
            if (empty($disk[$key])) {
                continue;
            }
            if ($key !== 'subtitles' && $key !== 'lyrics') {
                $disk[$key] = DiskRepository::allowUrl($disk[$key]);
                continue;
            }
            $disk[$key] = array_map(function ($item) use ($key) {
                $item['url'] = DiskRepository::allowUrl(url('./file/'.$key, ['id' => $item['id']]));
                return $item;
            }, $disk[$key]);
        }
        unset($disk['path']);
        return $disk;
    }

    public static function files(array $id): array {
        $items = [];
        foreach ($id as $i) {
            try {
                $items[] = self::file($i);
            } catch (\Exception) {}
        }
        return $items;
    }

}