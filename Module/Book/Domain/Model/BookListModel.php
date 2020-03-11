<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class BookListModel
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property integer $book_count
 * @property integer $click_count
 * @property integer $collect_count
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookListModel extends Model {

    public static function tableName() {
        return 'book_list';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'title' => 'required|string:0,50',
            'description' => 'string:0,200',
            'book_count' => 'int:0,99999',
            'click_count' => 'int:0,99999',
            'collect_count' => 'int:0,99999',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'title' => 'Title',
            'description' => 'Description',
            'book_count' => 'Book Count',
            'click_count' => 'Click Count',
            'collect_count' => 'Collect Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

}