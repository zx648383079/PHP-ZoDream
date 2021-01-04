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
 */
class GoodsIssueModel extends Model {
    public static function tableName() {
        return 'shop_goods_issue';
    }

    public function rules() {
        return [
            'goods_id' => 'required|int',
            'question' => 'required|string:0,255',
            'answer' => 'string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'question' => 'Question',
            'answer' => 'Answer',
        ];
    }
}