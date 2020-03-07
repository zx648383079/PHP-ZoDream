<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

/**
 * Class BookListItemModel
 * @property integer $id
 * @property integer $list_id
 * @property integer $book_id
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class ListItemModel extends Model {

    public static function tableName() {
        return 'book_list_item';
    }

    protected function rules() {
        return [
            'list_id' => 'required|int',
            'book_id' => 'required|int',
            'remark' => 'string:0,200',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'list_id' => 'List Id',
            'book_id' => 'Book Id',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}