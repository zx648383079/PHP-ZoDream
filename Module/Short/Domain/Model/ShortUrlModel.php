<?php
namespace Module\Short\Domain\Model;

use Domain\Model\Model;

/**
 * Class ShortUrlModel
 * @package Module\Short\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $short_url
 * @property string $source_url
 * @property integer $click_count
 * @property integer $status
 * @property integer $is_system
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $complete_short_url
 */
class ShortUrlModel extends Model {

    protected array $append = ['complete_short_url'];

    public static function tableName() {
        return 'short_url';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'short_url' => 'required|string:0,60',
            'source_url' => 'required|string:0,255',
            'click_count' => 'int',
            'status' => 'int:0,127',
            'is_system' => 'int:0,127',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'short_url' => 'Short Url',
            'source_url' => 'Source Url',
            'click_count' => 'Click Count',
            'status' => 'Status',
            'is_system' => 'Is System',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCompleteShortUrlAttribute() {
        return url('./'.$this->short_url, [], true, false);
    }
}