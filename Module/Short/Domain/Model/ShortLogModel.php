<?php
declare(strict_types=1);
namespace Module\Short\Domain\Model;

use Domain\Model\Model;

/**
 * Class ShortLogModel
 * @package Module\Short\Domain\Model
 * @property integer $id
 * @property integer $short_id
 * @property string $ip
 * @property integer $created_at
 */
class ShortLogModel extends Model {
    public static function tableName() {
        return 'short_log';
    }

    protected function rules() {
        return [
            'short_id' => 'int',
            'ip' => 'required|string:0,120',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'short_id' => 'Short Id',
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }
}