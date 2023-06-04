<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

class SkillEntity extends Entity {
    public static function tableName() {
        return 'gm_skill';
    }
}