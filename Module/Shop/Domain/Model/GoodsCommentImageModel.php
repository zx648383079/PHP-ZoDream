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
 * Class GoodsCommentImageModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $comment_id
 * @property string $file
 * @property integer $size
 *
 */
class GoodsCommentImageModel extends Model {
    public static function tableName() {
        return 'goods_comment_image';
    }
}