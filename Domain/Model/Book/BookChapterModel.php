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
 * Class BookChapterModel
 * @package Domain\Model\Book
 * @property string $title 章节名
 * @property string $content 章节内容
 * @property integer $words_count 字数
 * @property boolean $is_vip vip章节
 * @property float $price 章节价格
 *
 */
class BookChapterModel extends Model {
    public static function tableName() {
        return 'book_chapter';
    }
}