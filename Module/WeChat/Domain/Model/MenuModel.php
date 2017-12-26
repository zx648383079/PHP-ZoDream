<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * Class MenuModel
 * @package Module\WeChat\Domain\Model
 * @property integer $id
 */
class MenuModel extends Model {

    public static function tableName() {
        return 'wechat_menu';
    }
}