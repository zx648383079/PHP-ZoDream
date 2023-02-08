<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Disk\Domain\Model\ClientFileModel;
use Zodream\Disk\File;
use Zodream\Http\Http;

final class ClientRepository {

    public static function link(array $data) {
        $files = ClientFileModel::query()->pluck('md5');
        $http = new Http($data['server_url']);
        $http->parameters([
            'files' => $files,
            'file_count' => count($files),
            'upload_url' => $data['upload_url'],
            'download_url' => $data['download_url'],
            'ping_url' => $data['ping_url'],
        ])->setOption(CURLOPT_RETURNTRANSFER, 1)
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

    public static function upload() {

    }

    public static function uploadChunk() {

    }

    public static function uploadChunkMerge() {

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