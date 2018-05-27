<?php
namespace Module\CMS\Domain\Model;

use Zodream\Html\Page;
/**
 * Class ContentModel
 * @package Domain\Model\CMS
 * @property integer $id
 * @property string $title
 * @property integer $category_id
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property integer $status
 * @property integer $view_count
 * @property integer $created_at
 * @property integer $updated_at
 */
class ContentModel extends BaseModel {
    public static function tableName() {
        return 'cms_content_'.static::site();
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,100',
            'category_id' => 'required|int',
            'keywords' => 'string:0,255',
            'thumb' => 'string:0,255',
            'description' => 'string:0,255',
            'status' => 'int:0,9',
            'view_count' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'category_id' => 'Category Id',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'status' => 'Status',
            'view_count' => 'View Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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