<?php
namespace Domain\Model\Shopping;
/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/1/2
 * Time: 11:23
 */
use Domain\Model\Model;

/**
 * Class CategoryModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 */
class CategoryModel extends Model {
    public static function tableName() {
        return 'category';
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