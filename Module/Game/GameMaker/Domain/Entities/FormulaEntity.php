<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * 配方
 */
class FormulaEntity extends Entity {
    public static function tableName() {
        return 'gm_formula';
    }

}