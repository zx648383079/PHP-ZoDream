<?php
namespace Domain\Model\Book;

use Domain\Model\Model;

/**
 * Class BookCategoryModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $name
 */
class BookCategoryModel extends Model {
    public static function tableName() {
        return 'book_category';
    }


}