<?php
namespace Module\Book\Domain\Model;

use Module\Book\Domain\Entities\ListItemEntity;

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
class ListItemModel extends ListItemEntity {

    public function book() {
        return $this->hasOne(BookModel::class, 'id', 'book_id');
    }

}