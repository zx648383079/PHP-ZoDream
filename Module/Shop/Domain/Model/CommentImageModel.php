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
class CommentImageModel extends Model {
    public static function tableName() {
        return 'shop_comment_image';
    }
}