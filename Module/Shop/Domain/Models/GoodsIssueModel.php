<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class GoodsIssueModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property integer $goods_id
 * @property string $question
 * @property string $answer
 * @property integer $ask_id
 * @property integer $answer_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class GoodsIssueModel extends Model {
    public static function tableName(): string {
        return 'shop_goods_issue';
    }

    protected function rules(): array {
        return [
            'goods_id' => 'required|int',
            'question' => 'required|string:0,255',
            'answer' => 'string:0,255',
            'ask_id' => 'required|int',
            'answer_id' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'question' => 'Question',
            'answer' => 'Answer',
            'ask_id' => 'Ask Id',
            'answer_id' => 'Answer Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function goods() {
        return $this->hasOne(GoodsModel::class, 'id', 'goods_id')
            ->select('id', 'name', 'thumb');
    }
}