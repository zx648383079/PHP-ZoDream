<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Template\Module;
use Zodream\Disk\Directory;
use Zodream\Disk\FileObject;
use Zodream\Helpers\Json;

/**
 * Class ThemeModel
 * @package Module\Template\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property string $path
 * @property ThemeWeightModel[] $weights
 * @property ThemePageModel[] $pages
 */
class ThemeModel extends Model {
    public static function tableName() {
        return 'tpl_theme';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'path' => 'required|string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'path' => 'Path',
        ];
    }

    public static function isInstalled($name) {
        return static::where('name', $name)->count() > 0;
    }

    public static function install(ThemeModel $model) {
        $id = static::where('name', $model->name)->value('id');
        if ($id > 0) {
            $model->id = $id;
            $model->isNewRecord = false;
        } else{
            $model->save();
        }
        foreach ($model->pages as $page) {
            if (ThemePageModel::isInstalled($page->name, $model->id)) {
                continue;
            }
            $page->theme_id = $model->id;
            $page->save();
        }
        foreach ($model->weights as $weight) {
            if (ThemeWeightModel::isInstalled($weight->name, $model->id)) {
                continue;
            }
            $weight->theme_id = $model->id;
            $weight->save();
        }
    }

    /**
     * @return static[]
     */
    public static function findTheme() {
        return static::mapFolder(Module::templateFolder(), function ($item) {
            if ($item->hasFile('theme.json')) {
                return [static::createTheme($item)];
            }
            return false;
        });
    }

    protected static function mapFolder(Directory $dir, callable $cb) {
        $item = $cb($dir);
        if ($item !== false) {
            return $item;
        }
        $items = [];
        $dir->map(function (FileObject $file) use (&$items, $cb) {
            if (!($file instanceof Directory)) {
                return;
            }
            $items = array_merge($items, static::mapFolder($file, $cb));
        });
        return $items;
    }

    public static function createTheme(Directory $folder) {
        $data = Json::decode($folder->childFile('theme.json')->read());
        $data['path'] = $folder->getRelative(Module::templateFolder());
        $data['pages'] = array_map(function ($item) use ($data) {
            $item['path'] = $data['path'].'/'.$item['path'];
            return new ThemePageModel($item);
        }, $data['pages']);
        $data['weights'] = self::getWeight($data, $folder);
        return new static($data);
    }

    private static function getWeight($data, Directory $folder) {
        if (!isset($data['weights'])) {
            return [];
        }
        if (is_array($data['weights'])) {
            return array_map(function ($item) use ($data) {
                $item['path'] = $data['path'].'/'.$item['path'];
                return new ThemeWeightModel($item);
            }, $data['weights']);
        }
        return static::mapFolder($folder->directory($data['weights']), function ($item) {
            if ($item->hasFile('weight.json')) {
                $args = Json::decode($item->childFile('weight.json')->read());
                $args['path'] = $item->getRelative(Module::templateFolder());
                return [new ThemeWeightModel($args)];
            }
            return false;
        });
    }
}