<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * Class PageLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $url
 * @property integer $visit_count
 */
class PageLogModel extends Model {

    public static function tableName() {
        return 'ctr_page_log';
    }

    protected function rules() {
        return [
            'url' => 'required|string:0,255',
            'visit_count' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'url' => 'Url',
            'visit_count' => 'Visit Count',
        ];
    }
}
