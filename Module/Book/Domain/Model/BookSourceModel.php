<?php
declare(strict_types=1);
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

class BookSourceModel extends Model {
    public static function tableName()
    {
        return 'book_source';
    }
}