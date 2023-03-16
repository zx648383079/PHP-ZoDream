<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Disk\Domain\Model\ClientFileModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\FileSystem;
use Zodream\Http\Http;

final class ClientRepository {

    const SERVER_KEY = 'disk_server_url';

    public static function link(array $data) {
        $files = ClientFileModel::query()->pluck('md5');
        self::sendServer([
            'files' => $files,
            'file_count' => count($files),
            'upload_url' => $data['upload_url'],
            'download_url' => $data['download_url'],
            'ping_url' => $data['ping_url'],
        ], $data['server_url']);
        cache()->set(self::SERVER_KEY, $data['server_url']);
        return $data;
    }

    private static function sendServer(array $data, string $serverUrl = '') {
        if (empty($serverUrl)) {
            $serverUrl = cache(self::SERVER_KEY);
        }
        if (empty($serverUrl)) {
            throw new Exception('server error');
        }
        $data['token'] = config('disk.server_token');
        $http = new Http($serverUrl);
        $http->parameters($data)->setOption(CURLOPT_RETURNTRANSFER, 1)
            ->setOption(CURLOPT_FOLLOWLOCATION, 1)
            ->setOption(CURLOPT_AUTOREFERER, 1);
        $http->post();
    }

    public static function fileList(string $keywords = '') {
        return ClientFileModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function rootFolder(): Directory {
        return app_path('data/disk');
    }

    public static function cacheFolder(): Directory {
        return app_path('data/cache');
    }


    public static function uploadFile(array $fileData, string $md5, string $name) {
        set_time_limit(0);
        $location = md5($name.time()).FileSystem::getExtension($name, true);
        $file = self::rootFolder()->file($location);
        if (!move_uploaded_file($fileData['tmp_name'], $file->getFullName()) ||
            !$file->exist()) {
            throw new Exception('error move file');
        }
        if ($file->md5() !== $md5) {
            throw new Exception('uploaded chunk is not complete');
        }
        return self::saveUpload($name, $md5, $location, $file->size());
    }

    public static function uploadChunk(array $fileData, string $cacheName) {
        set_time_limit(0);
        $file = self::cacheFolder()->file($cacheName);
        if (!move_uploaded_file($fileData['tmp_name'], $file->getFullName()) ||
            !$file->exist()) {
            throw new Exception('error move file');
        }
        return [
            'name' => $cacheName,
            'size' => $file->size(),
        ];
    }

    public static function uploadChunkMerge(string $md5, string $name, array $chunkNames) {
        $location = md5($name.time()).FileSystem::getExtension($name, true);
        $file = self::rootFolder()->file($location);
        foreach ($chunkNames as $i => $cacheName) {
            $cacheFile = self::cacheFolder()->file($cacheName);
            if (!$cacheFile->exist()) {
                throw new Exception('not found chunk');
            }
            $file->write($cacheFile->read(), $i > 0 ? FILE_APPEND : false);
            $cacheFile->delete();
        }
        if ($file->md5() !== $md5) {
            throw new Exception('uploaded chunk is not complete');
        }
        return self::saveUpload($name, $md5, $location, $file->size());
    }

    private static function saveUpload(string $name, string $md5, string $location, int $size) {
        $fileModel = ClientFileModel::createOrThrow([
            'name' => $name,
            'extension' => FileSystem::getExtension($name),
            'md5' => $md5,
            'location' => $location,
            'size' => $size,
        ], '添加失败');
        // TODO 上报主服务器
        self::sendServer([
            'file' => $fileModel->toArray()
        ]);
        return $fileModel->toArray();
    }

    public static function download(string $md5) {
        $model = ClientFileModel::where('md5', $md5)->first();
        if (empty($model)) {
            throw new \Exception('file error');
        }
        $file = new File($model->location);
        $file->setExtension($model->extension)
            ->setName($model->name);
        return $file;
    }
}