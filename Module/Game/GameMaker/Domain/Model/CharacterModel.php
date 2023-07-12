<?php
namespace Module\Game\GameMaker\Domain\Model;


use Module\Game\GameMaker\Domain\Entities\CharacterEntity;
use Module\Game\GameMaker\Domain\Entities\CharacterIdentityEntity;

/**
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $project_id
 * @property string $nickname
 * @property integer $sex
 * @property integer $grade
 * @property string $money
 * @property string $exp
 * @property integer $x
 * @property integer $y
 * @property integer $updated_at
 * @property integer $created_at
 */
class CharacterModel extends CharacterEntity {


    public function identity() {
        return $this->hasOne(CharacterIdentityEntity::class, 'id', 'identity_id');
    }
}