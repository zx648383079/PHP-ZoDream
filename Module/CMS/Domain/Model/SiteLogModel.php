<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Model;

use Module\CMS\Domain\Entities\SiteLogEntity;

/**
 * @property integer $id
 * @property integer $model_id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property integer $created_at
 */
class SiteLogModel extends SiteLogEntity {

}