<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class GoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $content
 * @property integer $rank
 * @property integer $user_id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $create_at
 *
 */
class CommentModel extends Model {

    protected $append = ['user', 'images', 'goods'];

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
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'item_id');
    }

    public function images() {
        return $this->hasMany(CommentImageModel::class, 'comment_id', 'id');
    }
}