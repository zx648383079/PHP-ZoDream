<?php
namespace Module\Shop\Domain\Models;
/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/1/2
 * Time: 11:23
 */
use Module\Shop\Domain\Entities\CategoryEntity;

/**
 * Class CategoryModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 */
class CategorySimpleModel extends CategoryEntity {

    public static function query() {
        return parent::query()->select(['id', 'name']);
    }
}