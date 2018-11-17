<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;

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
class CommentModel extends Model {
    public static function tableName() {
        return 'shop_comment';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'item_type' => 'int:0,99',
            'item_id' => 'required|int',
            'title' => 'required|string:0,255',
            'content' => 'required|string:0,255',
            'rank' => 'int:0,99',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'title' => 'Title',
            'content' => 'Content',
            'rank' => 'Rank',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    public function goods() {
        return $this->hasOne(GoodsModel::class, 'id', 'item_id');
    }

    public function images() {
        return $this->hasMany(CommentImageModel::class, 'comment_id', 'id');
    }
}