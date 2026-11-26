<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\BrandEntity;

/**
 * 品牌
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property string $logo
 * @property string $app_logo
 * @property string $url
 */
class BrandModel extends BrandEntity {

}