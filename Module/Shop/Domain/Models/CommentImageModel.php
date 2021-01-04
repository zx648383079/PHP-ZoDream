<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class GoodsCommentImageModel
 * @property integer $id
 * @property integer $comment_id
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class CommentImageModel extends Model {
    public static function tableName() {
        return 'shop_comment_image';
    }

    public function rules() {
        return [
            'comment_id' => 'required|int',
            'image' => 'required|string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'comment_id' => 'Comment Id',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}