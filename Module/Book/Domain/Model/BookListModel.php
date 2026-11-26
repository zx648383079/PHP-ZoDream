<?php
declare(strict_types=1);
namespace Module\Book\Domain\Model;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Book\Domain\Entities\ListEntity;

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
class BookListModel extends ListEntity {


    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function items() {
        return $this->hasMany(ListItemModel::class, 'list_id', 'id')->with('book')
            ->take(4);
    }

}