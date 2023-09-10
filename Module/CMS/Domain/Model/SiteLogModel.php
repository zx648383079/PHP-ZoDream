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

    const TYPE_CATEGORY = 0;
    const TYPE_ARTICLE = 1;
    const TYPE_COMMENT = 2;

    const ACTION_AGREE = 1;
    const ACTION_DISAGREE = 2;

    public static function tableName(): string {
        return 'cms_log_'.CMSRepository::siteId();
    }

    protected function rules(): array {
        return [
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'model_id' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'user_id' => 'User Id',
            'action' => 'Action',
            'created_at' => 'Created At',
        ];
    }
}