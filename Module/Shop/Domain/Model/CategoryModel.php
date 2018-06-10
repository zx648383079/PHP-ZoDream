<?php
namespace Module\Shop\Domain\Model;
/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/1/2
 * Time: 11:23
 */
use Domain\Model\Model;

/**
 * Class CategoryModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property integer $parent_id
 * @property integer $position
 */
class CategoryModel extends Model {
    public static function tableName() {
        return 'shop_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'description' => 'string:0,200',
            'parent_id' => 'int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'parent_id' => 'Parent Id',
            'position' => 'Position',
        ];
    }

    /**
     * @return array
     */
    public function getChildren() {
        return [];
    }

    /**
     * @param CategoryModel $model
     * @return bool
     */
    public function isChild(CategoryModel $model) {
        return $this->isChildById($model->id);
    }

    public function isChildById($id) {
        return in_array($id, $this->getChildren());
    }

    /**
     * @param BaseGoodsModel $goods
     * @return bool
     */
    public function isChildGoods(BaseGoodsModel $goods) {
        return $this->isChildById($goods->category_id);
    }
}