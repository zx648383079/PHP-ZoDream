<?php
namespace Module\SEO\Domain\Repositories;

use Exception;
use Zodream\Disk\File;
use Zodream\Disk\ZipStream;
use Zodream\Module\Gzo\Domain\GenerateModel;
use Zodream\Service\Factory;

class SEORepository {

    public static function sqlFolder() {
        return Factory::root()->directory('data/sql');
    }

    public static function clearSql() {
        static::sqlFolder()->delete();
    }

    public static function backUpSql($zip = false) {
        $root = static::sqlFolder();
        $root->create();
        $file = $root->file(date('Y-m-d').'.sql');
        set_time_limit(0);
        if ((!$file->exist() || $file->modifyTime() < (time() - 60))
            && !GenerateModel::schema()
                ->export($file)) {
            throw new Exception('导出失败！');
        }
        $zip_file = $root->file(date('Y-m-d').'.zip');
        ZipStream::create($zip_file)->addFile($file)->close();
    }

    public static function sqlFiles() {
        $root = static::sqlFolder();
        if (!$root->exist()) {
            return [];
        }
        $items = [];
        $root->map(function ($file) use (&$items) {
            if ($file instanceof File) {
                $items[] = [
                    'name' => $file->getName(),
                    'size' => $file->size(),
                    'created_at' => $file->createTime(),
                ];
            }
        });
        return $items;
    }
}