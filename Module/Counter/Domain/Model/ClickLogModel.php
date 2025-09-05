<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Module\Counter\Domain\Events\CounterState;

/**
 * Class ClickLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property integer $log_id
 * @property string $x
 * @property string $y
 * @property string $tag
 * @property string $tag_url
 * @property integer $created_at
 */
class ClickLogModel extends Model {

    public static function tableName(): string {
        return 'ctr_click_log';
    }

    protected function rules(): array {
        return [
            'log_id' => 'required|int',
            'x' => 'string:0,100',
            'y' => 'string:0,100',
            'tag' => 'required|string:0,120',
            'tag_url' => 'string:0,120',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'log_id' => 'Log Id',
            'x' => 'X',
            'y' => 'Y',
            'tag' => 'Tag',
            'tag_url' => 'Tag Url',
            'created_at' => 'Created At',
        ];
    }

    public static function log(CounterState $state) {

    }

}
