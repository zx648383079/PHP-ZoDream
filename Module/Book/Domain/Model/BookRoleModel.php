<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
/**
 * Class BookRoleModel
 * @property integer $id
 * @property integer $book_id
 * @property string $name
 * @property string $avatar
 * @property string $description
 */
class BookRoleModel extends Model {

    public static function tableName() {
        return 'book_role';
    }

    protected function rules() {
        return [
            'book_id' => 'required|int',
            'name' => 'required|string:0,50',
            'avatar' => 'string:0,200',
            'description' => 'string:0,200',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'book_id' => 'Book Id',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'description' => 'Description',
        ];
    }

}