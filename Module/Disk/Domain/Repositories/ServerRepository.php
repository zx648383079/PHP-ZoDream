<?php
declare(strict_types=1);
namespace Module\Disk\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Disk\Domain\Model\FileModel;
use Module\Disk\Domain\Model\ServerFileModel;
use Module\Disk\Domain\Model\ServerModel;

final class ServerRepository {

    public static function serverList(string $keywords = '') {
        return ServerModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'ip');
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function saveServer(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ServerModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function fileList(string $keywords = '') {
        return FileModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function saveFile(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = FileModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function removeFile(array|int $id) {
        FileModel::where('id', $id)->update([
            'deleted_at' => time()
        ]);
    }

    public static function linkClient(array $data, int $serverId) {
        $request = request();
        $model = ServerModel::findOrThrow($serverId);
        $model->upload_url = $data['upload_url'];
        $model->download_url = $data['download_url'];
        $model->ping_url = $data['ping_url'];
        $model->ip = $request->ip();
        $model->port = $data['port'];
        $model->status = 1;
        $model->save();
        /// TODO
        $items = array_chunk($data['files'], 100);
        ServerFileModel::where('server_id', $serverId)
            ->delete();
        foreach ($items as $chunks) {
            $ids = FileModel::whereIn('md5', $chunks)->pluck('id');
            ServerFileModel::query()->insert(
                array_map(function ($fileId) use ($serverId) {
                    return [
                        'file_id' => $fileId,
                        'server_id' => $serverId
                    ];
                }, $ids)
            );
        }
    }

    public static function checkFile(string $md5) {
        $model = FileModel::where('md5', $md5)->first();

    }

    public static function asyncFile(array $data, int $serverId) {
        $model = FileModel::where('md5', $data['md5'])->first();
        if (empty($model)) {
            $model = FileModel::createOrThrow([
                'name' => $data['name'],
                'extension' => $data['extension'],
                'md5' => $data['md5'],
                'size' => $data['size'],
            ]);
        }
        $link = ServerFileModel::where('file_id', $model->id)->where('server_id', $serverId)->first();
        if (empty($link)) {
            $link = ServerFileModel::createOrThrow([
               'server_id' => $serverId,
               'file_id' => $model->id,
            ]);
        }
    }

    public static function findServer(string $token): ServerModel {
        $model = ServerModel::where('token', $token)->first();
        if (empty($model)) {
            throw new \Exception('token error');
        }
        return $model;
    }
}