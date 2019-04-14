<?php
namespace Module\Shop\Domain\Models;
/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/1/2
 * Time: 11:23
 */
use Domain\Model\Model;
use Zodream\Html\Tree;
use Zodream\Helpers\Tree as TreeHelper;

/**
 * Class CategoryModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property string $icon
 * @property integer $parent_id
 * @property integer $position
 * @property string $banner
 * @property string $app_banner
 * @property array $children
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
            'icon' => 'string:0,200',
            'parent_id' => 'int',
            'position' => 'int:0,999',
            'banner' => 'string:0,200',
            'app_banner' => 'string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'keywords' => '关键字',
            'description' => '简介',
            'icon' => '图标',
            'parent_id' => '上级',
            'position' => '排序',
            'banner' => 'Banner',
            'app_banner' => 'App Banner',
        ];
    }

    /**
     * @return array
     */
    public function getChildrenAttribute() {
        return TreeHelper::getTreeChild(static::cacheLevel(), $this->id);
    }

    public function getFamily() {
        $data = $this->children;
        $data[] = $this->id;
        return $data;
    }

    /**
     * @param CategoryModel $model
     * @return bool
     */
    public function isChild(CategoryModel $model) {
        return $this->isChildById($model->id);
    }

    public function isChildById($id) {
        return in_array($id, $this->children);
    }

    /**
     * @param GoodsModel $goods
     * @return bool
     */
    public function isChildGoods(GoodsModel $goods) {
        return $this->isChildById($goods->cat_id);
    }


    /**
     * @return Tree
     * @throws \Exception
     */
    public static function tree() {
        return new Tree(static::query()->all());
    }


    public static function cacheTree() {
        return cache()->getOrSet('shop_category_tree', function () {
            return self::tree()->makeIdTree();
        });
    }

    public static function cacheLevel() {
        return cache()->getOrSet('shop_category_level', function () {
            return self::tree()->makeTreeForHtml();
        });
    }

    /**
     * 获取子代树
     * @param $id
     * @param bool $data
     * @return array
     */
    public static function getChildrenItem($id, $data = true) {
        if ($data === true) {
            $data = self::cacheTree();
        }
        if (isset($data[$id])) {
            return isset($data[$id]['children']) ? $data[$id]['children'] : [];
        }
        foreach ($data as $item) {
            if (!isset($item['children'])) {
                continue;
            }
            $args = self::getChildrenItem($id, $item['children']);
            if (is_array($args)) {
                return $args;
            }
        }
        return false;
    }

    public static function getChildrenWithParent($id) {
        $data = TreeHelper::getTreeChild(static::cacheLevel(), $id);
        $data[] = $id;
        return $data;
    }

    public static function getParentWidthSelf($id) {
        $data = TreeHelper::getTreeParent(static::cacheLevel(), $id);
        $data[] = $id;
        return $data;
    }
}