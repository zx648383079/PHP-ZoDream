<?php
namespace Module\CMS\Domain\Model;

/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property string $name
 * @property string $field
 * @property integer $model_id
 * @property integer $type
 * @property integer $length
 * @property integer $position
 * @property integer $form_type
 * @property string $match
 * @property string $tip_message
 * @property string $error_message
 * @property string $setting
 */
class ModelFieldModel extends BaseModel {

    public $type_list = [
        'text' => '文本字段',
        'textarea' => '多行文本',
        'editor' => '编辑器',
        'radio' => '单选按钮',
        'select' => '下拉选择',
        'checkbox' => '复选框',
        'color' => '颜色选取',
        'date' => '日期时间',
        'file' => '单文件',
        'files' => '多文件',
        'linkage' => '联动菜单'
    ];

    public $match_list = [

    ];

    public static function tableName() {
        return 'cms_model_field';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'field' => 'required|string:0,100',
            'model_id' => 'required|int',
            'type' => 'int:0,9',
            'length' => 'int:0,999',
            'position' => 'int:0,999',
            'form_type' => 'int:0,999',
            'match' => 'string:0,255',
            'tip_message' => 'string:0,255',
            'error_message' => 'string:0,255',
            'setting' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'field' => 'Field',
            'model_id' => 'Model Id',
            'type' => 'Type',
            'length' => 'Length',
            'position' => 'Position',
            'form_type' => 'Form Type',
            'match' => 'Match',
            'tip_message' => 'Tip Message',
            'error_message' => 'Error Message',
            'setting' => 'Setting',
        ];
    }

    /**
     * @return ModelModel
     */
    public function getModel() {
        return $this->hasOne(ModelModel::class, 'id', 'model_id');
    }

    public function insert() {
        parent::insert();
        $table = $this->getModel()->getContentExtendTable();
        $table->set($this->field)->comment($this->name);
        return $table->alert();
    }

    public function update() {
        parent::update();
        $table = $this->getModel()->getContentExtendTable();
        $table->set($this->field)->setOldField($this->_oldData['field'])->comment($this->name);
        return $table->alert();
    }

    public function delete($where = null, $parameters = array()) {
        parent::delete($where, $parameters);
        $table = $this->getModel()->getContentExtendTable();
        $table->set($this->field);
        return $table->dropColumn();
    }
}