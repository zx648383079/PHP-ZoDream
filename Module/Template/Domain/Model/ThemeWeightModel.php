<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use Module\Template\Module;
use Zodream\Disk\Directory;


/**
 * 安装的部件列表
 * @package Module\Template
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $type
 * @property string $path
 * @property integer $editable
 * @property integer $theme_id
 */
class ThemeWeightModel extends Model {

    const TYPE_BASIC = 0;
    const TYPE_ADVANCE = 1;

    const ADAPT_ALL = 0; // 自适应
    const ADAPT_PC = 1;  // 适应PC
    const ADAPT_MOBILE = 2; // 适应手机

    public static function tableName() {
        return 'tpl_theme_weight';
    }


    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'description' => 'string:0,200',
            'thumb' => 'string:0,100',
            'type' => 'int:0,127',
            'adapt_to' => 'int:0,9',
            'editable' => '',
            'theme_id' => 'required|int',
            'path' => 'string:0,200',
        ];
    }

    protected function labels() {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'thumb' => 'Thumb',
            'type' => 'Type',
            'editable' => 'Editable',
            'theme_id' => 'Theme Id',
            'path' => 'Path',
        ];
    }


    public function getDirectoryAttribute() {
        if (file_exists($this->path)) {
            return new Directory(is_dir($this->path)
                ? $this->path : dirname($this->path));
        }
        return Module::templateFolder($this->path);
    }


    /**
     * @param $theme_id
     * @return array
     */
    public static function groupByType($theme_id) {
        $data = self::where('theme_id', $theme_id)->get();
        $args = [];
        foreach ($data as $item) {
            $args[$item->type][] = $item;
        }
        return $args;
    }

    /**
     * 是否已安装
     * @param $name
     * @param $theme_id
     * @return bool
     */
    public static function isInstalled($name, $theme_id) {
        return static::where('name', $name)->where('theme_id', $theme_id)->count() > 0;
    }

    /**
     * 创建
     * @param $name
     * @param $theme_id
     * @param $path
     * @param $thumb
     * @param null $description
     * @param bool $editable 是否允许编辑
     * @param int $type
     * @return static
     */
    public static function install($name,
                                   $theme_id,
                                   $path = null,
                                   $thumb = null,
                                   $description = null,
                                   $editable = true,
                                   $type = self::TYPE_BASIC) {
        if (is_array($name)) {
            extract($name);
        }
        $path = (string)$path;
        $editable = intval($editable);
        return static::create(compact('name', 'theme_id', 'path',
            'description', 'type', 'thumb', 'editable'));
    }
}