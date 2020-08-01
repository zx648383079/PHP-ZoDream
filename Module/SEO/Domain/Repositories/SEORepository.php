<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Repositories;

use Exception;
use Module\SEO\Domain\Model\OptionModel;
use Zodream\Disk\File;
use Zodream\Disk\ZipStream;
use Zodream\Module\Gzo\Domain\GenerateModel;
use Zodream\Service\Factory;

class SEORepository {

    public static function storeItems() {
        return [['name' => '默认', 'value' => 'default'], ['name' => '用户', 'value' => 'auth'], ['name' => '页面', 'value' => 'pages'], ['name' => '部件', 'value' => 'nodes'], ];
    }

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

    public static function clearCache(array $store = []) {
        if (empty($store)) {
            cache()->delete();
            return;
        }
        foreach ($store as $item) {
            if (empty($item)) {
                continue;
            }
            if ($item === 'default') {
                cache()->delete();
                continue;
            }
            cache()->store($item)->delete();
        }
    }

    public static function saveNewOption($data) {
        if (empty($data) || !is_array($data) || !isset($data['name'])) {
            return;
        }
        if (empty($data['name'])) {
            return;
        }
        if (OptionModel::where('name', $data['name'])->count() > 0) {
            return;
        }
        if ($data['type'] == 'group') {
            return OptionModel::create([
                'name' => $data['name'],
                'type' => 'group'
            ]);
        }
        if (empty($data['code']) || $data['parent_id'] < 1) {
            return;
        }
        if (OptionModel::where('code', $data['code'])->count() > 0) {
            return;
        }
        OptionModel::create($data);
    }

    public static function saveOption($data) {
        if (empty($data)) {
            return;
        }
        foreach ($data as $id => $value) {
            OptionModel::where('id', $id)->update(compact('value'));
        }
    }
}