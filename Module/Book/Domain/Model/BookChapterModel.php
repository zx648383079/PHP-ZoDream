<?php
namespace Module\Book\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/10/15
 * Time: 21:00
 */
use Domain\Model\Model;
use Zodream\Service\Routing\Url;

/**
 * Class BookChapterModel
 * @package Domain\Model\Book
 * @property integer $id
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

    public function body() {
        return $this->hasOne(BookChapterBodyModel::class, 'id', 'id');
    }

    public function getUrlAttribute() {
        return Url::to('book/home/detail', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return Url::to('book/wap/detail', ['id' => $this->id]);
    }
}