<?php
declare(strict_types = 1);
namespace Module\Auth\Domain\Advanced\Entities;

use Domain\Entities\Entity;

class RankEntity extends Entity {
    
    public static function tableName(): string {
        return 'adv_rank';
    }

    protected function rules(): array {
        return [
            'level' => 'required|int',
            'name' => 'required|string:0,32',
            'icon' => 'string:0,255',
            'configure' => 'string:0,200',
            'exp' => 'required|int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'level' => 'Level',
            'name' => 'Name',
            'icon' => 'Icon',
            'configure' => 'Configure',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}