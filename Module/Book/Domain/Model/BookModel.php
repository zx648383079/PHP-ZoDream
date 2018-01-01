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
 * Class BookModel
 * @package Domain\Model\Book
 * @property string $name
 * @property string $cover 封面
 * @property string $description 简介
 * @property integer $words_count 字数
 * @property integer $user_id 作者
 */
class BookModel extends Model {
    public static function tableName() {
        return 'book';
    }

    public function getChapters() {
        return $this->hasMany(BookChapterModel::className(), 'book_id', 'id');
    }

    public function category() {
        return $this->hasOne(BookCategoryModel::className(), 'id', 'cat_id');
    }

    public function getCoverAttribute() {
        $cover = $this->getAttributeValue('cover');
        if (!empty($cover)) {
            return $cover;
        }
        return '/assets/images/book_default.jpg';
    }

    public function getUrlAttribute() {
        return Url::to('book/home/chapter', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return Url::to('book/wap/chapter', ['id' => $this->id]);
    }

    public function getStatusAttribute() {
        return $this->over_at > 0 ? '已完本' : '连载中';
    }

    /**
     * @return BookChapterModel
     */
    public function getLastChapterAttribute() {
        return BookChapterModel::where('book_id', $this->id)->order('created_at', 'desc')->one();
    }
}