<?php
declare(strict_types=1);
namespace Module\Book\Domain\Model;

use Module\Book\Domain\Entities\HistoryEntity;


/**
 * Class BookHistoryModel
 * @package Module\Book\Domain\Model
 * @property integer $user_id
 * @property integer $book_id
 * @property integer $chapter_id
 * @property integer $progress
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookHistoryModel extends HistoryEntity {

    protected array $append = ['book', 'chapter'];


    public function book() {
        return $this->hasOne(BookSimpleModel::class, 'id', 'book_id');
    }

    public function chapter() {
        return $this->hasOne(ChapterSimpleModel::class,
            'id', 'chapter_id');
    }
}