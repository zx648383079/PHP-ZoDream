<?php
namespace Module\CMS\Domain\Model;

use Zodream\Database\Schema\Table;
/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property integer $type
 * @property string $table
 * @property string $name
 * @property integer $position
 * @property string $category_template
 * @property string $list_template
 * @property string $show_template
 * @property string $setting
 */
class ModelModel extends BaseModel {

    public static function tableName() {
        return 'model';
    }

    /**
     * @return ModelFieldModel[]
     */
    public function getFields() {
        $data = array();
        $args = $this->hasMany(ModelFieldModel::class, 'model_id');
        foreach ($args as $item){
            /** @var $item ModelFieldModel */
            $data[$item->field] = $item;
        }
        return $data;
    }

    public function getContentExtendTable() {
        return new Table(static::getExtendTable($this->table));
    }

    public function createTable() {
        $table = $this->getContentExtendTable();
        $table->set('id')->int(10)->pk()->ai();
        $table->set('content')->mediumtext();
        return $table->create();
    }
}