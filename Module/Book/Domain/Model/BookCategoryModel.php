<?php
namespace Module\Book\Domain\Model;

use Module\Book\Domain\Entities\CategoryEntity;


/**
 * Class BookCategoryModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 */
class BookCategoryModel extends CategoryEntity {

    public function book() {
        return $this->hasMany(BookModel::class, 'cat_id', 'id');
    }

    public function getRealNameAttribute() {
        return str_replace('·', '', $this->name);
    }

    public function getBookListAttribute() {
        return BookModel::where('cat_id', $this->id)->orderBy('click_count', SORT_DESC)->take(10)->get();
    }

    public function getMonthBookAttribute() {
        return BookModel::where('cat_id', $this->id)->orderBy('click_count', SORT_DESC)->take(10)->get();
    }

    public function getWeekBookAttribute() {
        return BookModel::where('cat_id', $this->id)->orderBy('click_count', SORT_DESC)->take(10)->get();
    }

    public function getUrlAttribute() {
        return url('./category', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return url('./mobile/category', ['id' => $this->id]);
    }

    public function getRecommendBookAttribute() {
        return $this->recommend_book = BookModel::ofClassify()->where('cat_id', $this->id)->take(4)->get();
    }

    public function getBestRecommendBookAttribute() {
        return $this->best_recommend_book = BookModel::ofClassify()->where('cat_id', $this->id)->take(5)->get();
    }

    /**
     * 新建
     * @param $name
     * @return static
     */
    public static function findOrNewByName(string $name) {
        $name = trim($name);
        if (empty($name)) {
            return static::orderBy('id', SORT_ASC)->first();
        }
        $model = static::where('name', $name)->first();
        if (!empty($model)) {
            return $model;
        }
        return static::create([
            'name' => $name
        ]);
    }

    public static function findIdByName(string $name) {
        return static::findOrNewByName($name)->id;
    }
}