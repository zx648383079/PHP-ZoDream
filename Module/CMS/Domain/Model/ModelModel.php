<?php
namespace Module\CMS\Domain\Model;

use Zodream\Database\Schema\Table;
/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property string $name
 * @property string $table
 * @property integer $type
 * @property integer $position
 * @property string $category_template
 * @property string $list_template
 * @property string $show_template
 * @property string $setting
 */
class ModelModel extends BaseModel {

    public static function tableName() {
        return 'cms_model';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'table' => 'required|string:0,100',
            'type' => 'int:0,9',
            'position' => 'int:0,999',
            'category_template' => 'string:0,20',
            'list_template' => 'string:0,20',
            'show_template' => 'string:0,20',
            'setting' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'table' => 'Table',
            'type' => 'Type',
            'position' => 'Position',
            'category_template' => 'Category Template',
            'list_template' => 'List Template',
            'show_template' => 'Show Template',
            'setting' => 'Setting',
        ];
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