<?php
namespace Module\Template\Domain\Model;

use Domain\Model\Model;

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

    public static function tableName() {
        return 'weight';
    }

}