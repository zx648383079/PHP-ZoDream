<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class RecipeEntity extends Entity {
    public static function tableName() {
        return 'eat_recipe';
    }
}