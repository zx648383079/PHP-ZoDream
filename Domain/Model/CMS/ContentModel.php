<?php
namespace Domain\Model\CMS;

use Zodream\Html\Page;
/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property integer $status
 * @property integer $hit_count
 * @property integer $update_at
 * @property integer $create_at
 */
class ContentModel extends BaseModel {
    public static function tableName() {
        return 'content_'.static::site();
    }

    /**
     * @param $words
     * @return Page
     */
    public static function search($words) {
        return static::findPage();
    }

    /**
     * @return CategoryModel
     */
    public function getCategory() {
        return $this->hasOne(CategoryModel::class, 'id', 'category_id');
    }

    public function save() {
        $data = array();
        foreach ($this->getCategory()
                     ->getModel()->getFields() as $field) {
            $value = $this->get($field->field);
            if ($field->valid($value)) {
                $data[$field->field] = $value;
                continue;
            }
            $this->setError($field->field, $field->error_message);
        }
        $isNew = $this->isNewRecord;
        $result = parent::save();
        if (empty($result)) {
            return $result;
        }
        $record = $this->getCategory()
            ->getModel()
            ->getContentExtendTable()
            ->record();
        $record->set($data);
        if ($isNew) {
            return $record->insert();
        }
        return $record->where(['id' => $this->id])
            ->update();
    }
}