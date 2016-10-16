<?php
namespace Domain\Model\Book;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/10/15
 * Time: 21:00
 */
use Domain\Model\Model;

/**
 * Class BookCategoryModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $name
 */
class BookCategoryModel extends Model {
    public static $table = 'book_category';
}