<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;


use Domain\Model\SearchModel;
use Exception;
use Module\Shop\Domain\Models\AttributeGroupModel;

class AttributeRepository {


    public static function groupAll() {
        return AttributeGroupModel::query()->get('id', 'name');
    }
}