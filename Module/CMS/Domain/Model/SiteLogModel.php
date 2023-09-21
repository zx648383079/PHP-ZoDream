<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;
use Module\CMS\Domain\Repositories\CMSRepository;

/**
 * @property integer $id
 * @property integer $model_id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $action
 * @property integer $created_at
 */
class SiteLogModel extends Model {

    const ACTION_AGREE = 1;
    const ACTION_DISAGREE = 2;


}