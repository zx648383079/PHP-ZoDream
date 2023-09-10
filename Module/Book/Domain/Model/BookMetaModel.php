<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;

class BookMetaModel extends Model {

    public static function tableName(): string {
        return 'book_meta';
    }

}