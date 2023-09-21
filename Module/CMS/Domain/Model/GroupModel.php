<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Entities\GroupEntity;

/**
 * Class GroupModel
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $description
 */
class GroupModel extends GroupEntity {
    const TYPE_CATEGORY = 0;
    const TYPE_CONTENT = 1;

}