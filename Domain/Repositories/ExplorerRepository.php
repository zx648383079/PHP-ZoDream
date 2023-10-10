<?php
declare(strict_types=1);
namespace Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\StorageProvider;
use Zodream\Database\DB;
use Zodream\Disk\File;
use Zodream\Disk\FileSystem;
use Zodream\Html\Page;
use Zodream\Infrastructure\Contracts\Http\Output;

final class ExplorerRepository {

    protected static function storage(int|string $tag): StorageProvider|null {
        $i = is_numeric($tag) ? intval($tag) : (ord(strtolower($tag)) - 96);
        return match ($i) {
            1 => StorageProvider::publicStore(),
            2 => StorageProvider::privateStore(),
            default => null,
        };
    }

    public static function driveItems(): array {
        $root = app_path();
        $items = [
            'a' => (string)public_path()->childDirectory(config('view.asset_directory')),
            'b' => (string)$root->childDirectory('data/storage'),
        ];
        if (config()->has('disk')) {
            $path = config('disk.disk');
            $items['c'] = $root->childDirectory(empty($path) ? 'data/disk/file/' : $path);
        }
        return $items;
    }

    public static function driveList(): array {
        $items = [];
        $trans = trans('disk_drive');
        foreach (static::driveItems() as $key => $_) {
            $items[] = [
                'name' => is_array($trans) && isset($trans[$key]) ? $trans[$key] : sprintf('DATA(%s:)', $key),
                'path' => $key. ':',
                'isFolder' => true
            ];
        }
        return $items;
    }

    public static function search(string $path = '',
                                  string $keywords = '',
                                  string $filter = '',
                                  int $page = 1): array|Page {
        list($drive, $path, $folder) = static::splitPath($path);
        if (empty($drive)) {
            return static::driveList();
        }
        if (!is_dir($folder)) {
            return [];
        }
        $visualFolder = sprintf('%s:%s%s', $drive, $path !== '' ? '/' : '', $path);
        $items = [];
        FileSystem::eachFile($folder, function (string $name) use ($keywords, $filter,
            $folder, $visualFolder,
            &$items) {
            if (!static::isMatch($keywords, $name)) {
                return;
            }
            $fullName = sprintf('%s/%s', $folder, $name);
            $isFolder = is_dir($fullName);
            $ext = FileSystem::getExtension($name, true);
            if (!static::isFilterFile($filter, $isFolder, $ext)) {
                return;
            }
            $items[] = [
                'name' => $name,
                'path' => sprintf('%s/%s', $visualFolder, $name),
                'isFolder' => $isFolder,
                'size' => $isFolder ? null : FileSystem::size($fullName),
                'created_at' => FileSystem::lastModified($fullName)
            ];
        });
        return $items;
    }

    public static function download(Output $output, string $path): string {
        $fileName = static::toFileName($path);
        if (empty($fileName) || !is_file($fileName)) {
            $output->header->setContentDisposition('error.txt');
            return $output->custom(sprintf('Path [%s] is error', $path), 'txt');
        }
        return $output->file(new File($fileName));
    }

    public static function delete(string $path): void {
        list($drive, $path, $fileName) = static::splitPath($path);
        if (empty($fileName)) {
            throw new \Exception(sprintf('Path [%s] is error', $path));
        }
        if (is_dir($fileName)) {
            FileSystem::deleteDirectory($fileName);
            return;
        }
        if (!is_file($fileName)) {
            return;
        }
        FileSystem::delete($fileName);
        $storage = static::storage($drive);
        if (empty($storage)) {
            return;
        }
        $storage->removeFile($path);
    }

    /**
     * 获取真实地址
     * @param string $path
     * @return string
     */
    public static function toFileName(string $path): string {
        return static::splitPath($path)[2];
    }

    /**
     * 解析地址
     * @param string $path
     * @return string[] // [$drive: string, $path: string, $fileName: string]
     */
    public static function splitPath(string $path): array {
        if ($path === '' || $path === '/') {
            return ['', '', ''];
        }
        $args = explode(':', $path);
        $path = end($args);
        if (!empty($path)) {
            $path = FileSystem::filterPath($path, false);
        }
        $drive = 'a';
        if (count($args) > 1) {
            $drive = strtolower($args[0]);
        }
        $maps = static::driveItems();
        if (!isset($maps[$drive])) {
            return [$drive, ''];
        }
        $base = $maps[$drive];
        return [
            $drive,
            $path,
            $path === '' ? $base : sprintf('%s/%s', $base, $path),
        ];
    }

    protected static function isMatch(string $keywords, string $fileName): bool {
        if (empty($keywords)) {
            return true;
        }
        foreach (explode(' ', $keywords) as $item) {
            if (empty($item)) {
                continue;
            }
            if (str_contains($fileName, $item)) {
                return true;
            }
        }
        return false;
    }

    protected static function isFilterFile(string $filter, bool $isFolder, string $ext): bool {
        if (empty($filter)) {
            return true;
        }
        if ($filter === 'folder') {
            return $isFolder;
        }
        if ($isFolder) {
            return false;
        }
        if (empty($ext)) {
            return false;
        }
        $i = strpos($filter, '/');
        if ($i !== false) {
            $filter = substr($filter, 0, $i);
        }
        if ($filter === 'image') {
            return in_array($ext, FileRepository::config('imageAllowFiles'));
        }
        if ($filter === 'video') {
            return in_array($ext, FileRepository::config('videoAllowFiles'));
        }
        if ($filter === 'file') {
            return in_array($ext, FileRepository::config('fileAllowFiles'));
        }
        return false;
    }


    public static function storageSearch(string $keywords = '', int $tag = 0) {
        return DB::table(StorageProvider::FILE_TABLE)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, 'name', true, '', $keywords);
            })->when($tag > 0, function ($query) use ($tag) {
                $query->where('folder', $tag);
            })->orderBy('id', 'desc')->page();
    }

    public static function storageRemove(int|array $id): void {
        $items = DB::table(StorageProvider::FILE_TABLE)
            ->whereIn('id', (array)$id)->get();
        if (empty($items)) {
            throw new \Exception('file is error');
        }
        foreach ($items as $item) {
            $storage = static::storage($item['folder']);
            if (empty($storage)) {
                throw new \Exception('unknown folder');
            }
            $storage->remove($item);
        }
    }

    public static function storageReload(int $tag): void {
        $storage = static::storage($tag);
        if (empty($storage)) {
            throw new \Exception('unknown folder');
        }
        set_time_limit(0);
        $storage->reload();
    }

    public static function storageSync(int|array $id): void {
        $items = DB::table(StorageProvider::FILE_TABLE)
            ->whereIn('id', (array)$id)->get();
        if (empty($items)) {
            throw new \Exception('file is error');
        }
        foreach ($items as $item) {
            $storage = static::storage($item['folder']);
            if (empty($storage)) {
                throw new \Exception('unknown folder');
            }
            $storage->syncFile($item['path']);
        }
    }
}