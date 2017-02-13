<?php
namespace Domain\Model\CMS;

/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property integer $model_id
 * @property string $field
 * @property string $name
 * @property string $type
 * @property integer $length
 * @property integer $form_type
 * @property integer $position
 * @property string $match
 * @property string $tip_message
 * @property string $error_message
 * @property string $category_template
 * @property string $list_template
 * @property string $show_template
 * @property string $setting
 */
class ModelFieldModel extends BaseModel {

    public static function tableName() {
        return 'model_field';
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

    public function update($where = null, $args = null) {
        parent::update($where, $args);
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

    public function valid($value) {
        return true;
    }
}