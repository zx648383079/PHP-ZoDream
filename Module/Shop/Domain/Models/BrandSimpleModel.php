<?php
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\BrandEntity;

/**
 * 品牌
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $logo
 */
class BrandSimpleModel extends BrandEntity {

    public static function query() {
        return parent::query()->select(['id', 'name']);
    }
}