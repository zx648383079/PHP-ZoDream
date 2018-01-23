<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Zodream\Service\Routing\Url;

class BookAuthorModel extends Model {
    public static function tableName() {
        return 'book_author';
    }

    public function getUrlAttribute() {
        return Url::to('./home/author', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return Url::to('./wap/author', ['id' => $this->id]);
    }
}