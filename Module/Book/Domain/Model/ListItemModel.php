<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

/**
 * Class BookListItemModel
 * @property integer $id
 * @property integer $list_id
 * @property integer $book_id
 * @property string $remark
 * @property integer $star
 * @property integer $agree_count
 * @property integer $disagree_count
 * @property integer $agree_type {0:无，1:同意 2:不同意}
 * @property integer $created_at
 * @property integer $updated_at
 */
class ListItemModel extends Model {

    public static function tableName(): string {
        return 'book_list_item';
    }

    protected function rules(): array {
        return [
            'list_id' => 'required|int',
            'book_id' => 'required|int',
            'remark' => 'string:0,200',
            'star' => 'int:0,127',
            'agree_count' => 'int:0,99999',
            'disagree_count' => 'int:0,99999',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'list_id' => 'List Id',
            'book_id' => 'Book Id',
            'remark' => 'Remark',
            'star' => 'Star',
            'agree_count' => 'Agree',
            'disagree_count' => 'Disagree',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function book() {
        return $this->hasOne(BookModel::class, 'id', 'book_id');
    }

}