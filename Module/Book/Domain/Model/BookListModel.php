<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

/**
 * Class BookListModel
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}