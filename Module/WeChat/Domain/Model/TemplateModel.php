<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

class TemplateModel extends Model {
    public static function tableName() {
        return 'wechat_template';
    }
}