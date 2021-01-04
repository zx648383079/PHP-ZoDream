<?php
namespace Module\Demo\Domain\Repositories;

use Module\Demo\Domain\Model\PostModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\ZipStream;
use Zodream\Domain\Upload\UploadFile;


class PostRepository {

    public static function saveFile() {
        $folder = app_path()->directory('data/demo');
        $folder->create();
        $file = request()->file('file');
        if (empty($file)) {
            return '';
        }
        $upload = new UploadFile($file);
        $upload->setFile($folder->file($file['name']));
        if ($upload->save()) {
            return $file['name'];
        }
        throw new \Exception($upload->getError());
    }

    public static function file(PostModel $model) {
        return app_path()->file('data/demo/'. $model->file);
    }

    public static function folder($id) {
        if ($id instanceof PostModel) {
            $id = $id->id;
        }
        return app_path()->directory('data/demo/'. $id);
    }

    public static function unzipFile(PostModel $model) {
        $folder = static::folder($model);
        if ($folder->exist()) {
            $folder->delete();
        }
        $folder->create();
        $file = static::file($model);
        $zip = new ZipStream($file);
        $zip->extractTo($folder);
    }

    public static function fileMap(PostModel $model) {
        $folder = static::folder($model);
        return static::getFiles($folder);
    }

    public static function getFiles(Directory $folder) {
        $items = [];
        if (!$folder->exist()) {
            return $items;
        }
        $folder->map(function ($file) use (&$items) {
            if ($file instanceof File) {
                $items[] = [
                    'name' => $file->getName(),
                    'type' => 1,
                    'icon' => 'fa-file'
                ];
                return;
            }
            $items[] = [
                'name' => $file->getName(),
                'type' => 0,
                'icon' => 'fa-folder',
                'children' => static::getFiles($file)
            ];
        });
        return $items;
    }

}