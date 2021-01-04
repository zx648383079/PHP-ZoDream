<?php
namespace Module\Legwork\Domain\Entities;

use Domain\Entities\Entity;

class CategoryEntity extends Entity {
    public static function tableName() {
        return 'legwork_category';
    }

    public function rules() {
        return [
            'name' => 'required|string:0,100',
            'icon' => 'string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'icon' => '图标',
        ];
    }
}