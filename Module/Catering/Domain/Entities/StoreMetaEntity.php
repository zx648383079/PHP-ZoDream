<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $content
 */
class StoreMetaEntity extends Entity {
    public static function tableName(): string {
        return 'eat_store_meta';
    }

    protected function rules(): array {
        return [
            'store_id' => 'required|int',
            'name' => 'required|string:0,100',
            'content' => 'required',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'name' => 'Name',
            'content' => 'Content',
        ];
    }

}