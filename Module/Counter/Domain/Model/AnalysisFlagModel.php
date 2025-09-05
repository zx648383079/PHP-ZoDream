<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property integer $item_type
 * @property string $item_value
 * @property integer $flags
 * @property integer $user_id
 * @property integer $created_at
 */
class AnalysisFlagModel extends Model
{
    public static function tableName(): string
    {
        return 'ctr_analysis_flag';
    }

    protected function rules(): array {
        return [
            'item_type' => 'required|int:0,127',
            'item_value' => 'required|string:0,255',
            'flags' => 'required|int',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'item_type' => 'Item Type',
            'item_value' => 'Item Value',
            'flags' => 'Flags',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
    }
}