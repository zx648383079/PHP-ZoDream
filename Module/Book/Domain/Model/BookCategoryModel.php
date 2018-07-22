<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;
use Zodream\Infrastructure\Http\URL;

/**
 * Class BookCategoryModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 */
class BookCategoryModel extends Model {
    public static function tableName() {
        return 'book_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }

    public function book() {
        return $this->hasMany(BookModel::class, 'cat_id', 'id');
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
        return URL::to('./category', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return URL::to('./mobile/category', ['id' => $this->id]);
    }

    public function getRecommendBookAttribute() {
        return $this->recommend_book = BookModel::where('cat_id', $this->id)->limit(4)->all();
    }

    public function getBestRecommendBookAttribute() {
        return $this->best_recommend_book = BookModel::where('cat_id', $this->id)->limit(5)->all();
    }
}