<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class GoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $content
 * @property integer $star
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $create_at
 *
 */
class GoodsCommentModel extends Model {
    public static function tableName() {
        return 'goods_comment';
    }
}