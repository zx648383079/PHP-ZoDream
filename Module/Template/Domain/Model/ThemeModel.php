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

    /**
     * @return static[]
     */
    public static function findTheme() {
        return static::getTheme(Module::templateFolder());
    }

    protected static function getTheme(Directory $dir) {
        if ($dir->hasFile('theme.json')) {
            return [static::createTheme($dir)];
        }
        $items = [];
        $dir->map(function (FileObject $file) use (&$items) {
            if (!($file instanceof Directory)) {
                return;
            }
            $items = array_merge($items, static::getTheme($file));
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
        $data['weights'] = isset($data['weights']) ? array_map(function ($item) use ($data) {
            $item['path'] = $data['path'].'/'.$item['path'];
            return new ThemeWeightModel($item);
        }, $data['weights']) : [];
        return new static($data);
    }
}