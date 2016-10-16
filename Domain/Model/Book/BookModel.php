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
 * Class BookModel
 * @package Domain\Model\Book
 * @property string $name
 * @property string $cover 封面
 * @property string $description 简介
 * @property integer $words_count 字数
 * @property integer $user_id 作者
 */
class BookModel extends Model {
    public static $table = 'book';

    public function getChapters() {
        return $this->hasMany(BookChapterModel::$table, 'book_id');
    }
}