<?php
namespace Module\Legwork\Domain\Model;
/**
 * Created by PhpStorm.
 * User: ZoDream
 * Date: 2017/1/2
 * Time: 11:23
 */
use Module\Legwork\Domain\Entities\CategoryEntity;

/**
 * Class CategoryModel
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property integer $parent_id
 * @property array $children
 */
class CategoryModel extends CategoryEntity {

    public function getIconAttribute() {
        $thumb = $this->getAttributeSource('icon');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

}