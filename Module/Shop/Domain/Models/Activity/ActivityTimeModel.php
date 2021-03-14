<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;

/**
 * Class ActivityTimeModel
 * @package Module\Shop\Domain\Models\Activity
 * @property integer $id
 * @property string $title
 * @property string $start_at
 * @property string $end_at
 */
class ActivityTimeModel extends Model {

    public static function tableName() {
        return 'shop_activity_time';
    }

    public function rules() {
        return [
            'title' => 'required|string:0,40',
            'start_at' => '',
            'end_at' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => '名称',
            'start_at' => 'Start At',
            'end_at' => 'End At',
        ];
    }

    public function getStartAtAttribute() {
        return date('H:i', strtotime($this->getAttributeSource('start_at')));
    }

    public function getEndAtAttribute() {
        return date('H:i', strtotime($this->getAttributeSource('end_at')));
    }

}