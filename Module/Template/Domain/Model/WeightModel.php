<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;
use phpDocumentor\Reflection\Types\Self_;
use Zodream\Disk\Directory;
use Zodream\Disk\FileException;
use Zodream\Disk\FileObject;
use Zodream\Helpers\Str;

/**
 * 安装的部件列表
 * @package Module\Template
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $thumb
 * @property integer $type
 * @property string $path
 */
class WeightModel extends Model {

    const TYPE_BASIC = 0;
    const TYPE_ADVANCE = 1;

    public static function tableName() {
        return 'weight';
    }

    public function getPostConfigs() {

    }

    /**
     * @return mixed
     * @throws FileException
     */
    public function getInstance() {
        $path = $this->path;
        if (empty($path)) {
            throw new FileException($path);
        }
        if (is_dir($path)) {
            $path = rtrim($path, '/\\'). '/weight.php';
        }
        if (!is_file($path)) {
            return new $path;
        }
        include_once $path;
        $name = Str::studly($this->name).'Weight';
        return new $name;
    }


    public static function findWeights() {
        $directory = new Directory(dirname(dirname(__DIR__)) . '/UserInterface/weights');
        $directory->map(function (FileObject $file) {
            if (!($file instanceof Directory)) {
                return;
            }
            $data = json_decode($file->childFile('weight.json')->read());
            if (static::isInstalled($data['name'])) {
                return;
            }
            $data['thumb'] = $file->getAbsolute($data['thumb']);
            static::create($data);
        });
    }

    /**
     * 是否已安装
     * @param $name
     * @return bool
     */
    public static function isInstalled($name) {
        return static::where('name', $name)->count() > 0;
    }

    /**
     * 创建
     * @param $name
     * @param $path
     * @param $thumb
     * @param null $description
     * @param int $type
     * @return static
     */
    public static function install($name,
                            $path,
                            $thumb,
                            $description = null,
                            $type = self::TYPE_BASIC) {
        return static::create(compact('name', 'path',
            'description', 'type', 'thumb'));
    }
}