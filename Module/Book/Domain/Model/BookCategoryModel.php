<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Zodream\Service\Routing\Url;

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

    public function getRealNameAttribute() {
        return str_replace('·', '', $this->name);
    }

    public function getBookListAttribute() {
        return BookModel::where('cat_id', $this->id)->order('click_count', 'desc')->limit(10)->all();
    }

    public function getMonthBookAttribute() {
        return BookModel::where('cat_id', $this->id)->order('click_count', 'desc')->limit(10)->all();
    }

    public function getWeekBookAttribute() {
        return BookModel::where('cat_id', $this->id)->order('click_count', 'desc')->limit(10)->all();
    }

    public function getUrlAttribute() {
        return Url::to('book/home/category', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return Url::to('book/wap/category', ['id' => $this->id]);
    }
}