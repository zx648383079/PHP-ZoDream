<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Forum\Domain\Model\EmojiCategoryModel;
use Module\Forum\Domain\Model\EmojiModel;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\ZipStream;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;

class EmojiRepository {
    public static function getList(string $keywords = '', int $cat_id = 0) {
        return EmojiModel::with('category')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->when($cat_id > 0, function ($query) use ($cat_id) {
            $query->where('cat_id', $cat_id);
        })->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return EmojiModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = EmojiModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        EmojiModel::where('id', $id)->delete();
    }

    public static function catList(string $keywords = '') {
        return EmojiCategoryModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->get();
    }

    public static function getCategory(int $id) {
        return EmojiCategoryModel::findOrThrow($id, '数据有误');
    }

    public static function saveCategory(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = EmojiCategoryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function removeCategory(int $id) {
        EmojiCategoryModel::where('id', $id)->delete();
    }

    public static function findOrNewCategory(string $name, string $icon = '') {
        if (empty($name)) {
            return EmojiCategoryModel::query()->min('id');
        }
        $id = EmojiCategoryModel::where('name', $name)
            ->value('id');
        if ($id > 0) {
            return $id;
        }
        $model = EmojiCategoryModel::create(compact('name', 'icon'));
        return $model->id;
    }

    public static function import(File $file) {
        $zip = new ZipStream($file, \ZipArchive::RDONLY);
        $folder = $file->getDirectory()->directory('emoji'.time());
        $folder->create();
        $zip->extractTo($folder);
        $zip->close();
        static::mapFolder($folder);
        $folder->delete();
        $file->delete();
    }

    protected static function mapFolder(Directory $folder) {
        $file = $folder->file('map.json');
        if ($file->exist()) {
            static::importBatch($file);
            return;
        }
        $folder->map(function ($file) {
            if ($file instanceof Directory) {
                static::mapFolder($file);
            }
        });
    }

    protected static function importBatch(File $file) {
        $data = Json::decode($file->read());
        if (!isset($data['items']) || empty($data['items'])) {
            return;
        }
        $folder = 'assets/upload/emoji/';
        $category = static::findOrNewCategory($data['name'],
            isset($data['icon']) && !empty($data['icon']) ?
                url()->asset($folder.$data['icon']) : '');
        EmojiModel::query()->insert(array_map(function ($item) use ($category, $folder) {
            $type = isset($item['type']) && $item['type'] === 'text' ? EmojiModel::TYPE_TEXT : EmojiModel::TYPE_IMAGE;
            return [
                'cat_id' => $category,
                'name' => $item['title'],
                'type' => $type,
                'content' => $type === EmojiModel::TYPE_TEXT ? $item['title']
                    : url()->asset($folder.$item['file']),
            ];
        }, $data['items']));
        $newFolder = public_path()->directory($folder);
        $newFolder->create();
        $file->getDirectory()->map(function ($item) use ($newFolder) {
            if ($item instanceof File && $item->getExtension() !== 'json') {
                $item->move($newFolder->file($item->getName()));
            }
        });
    }

    public static function all() {
        return cache()->getOrSet('emoji_tree', function () {
           return Arr::format(EmojiCategoryModel::with('items')
               ->orderBy('id', 'asc')->get());
        });
    }
}