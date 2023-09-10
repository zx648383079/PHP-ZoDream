<?php
namespace Module\Book\Domain\Model;

use Domain\Model\Model;


/**
 * Class BookCategoryModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 */
class BookCategoryModel extends Model {
    public static function tableName(): string {
        return 'book_category';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '分类',
            'created_at' => '创建时间',
        ];
    }

    public function book() {
        return $this->hasMany(BookModel::class, 'cat_id', 'id');
    }

    public function getRealNameAttribute() {
        return str_replace('·', '', $this->name);
    }

    public function getBookListAttribute() {
        return BookModel::where('cat_id', $this->id)->orderBy('click_count', 'desc')->limit(10)->all();
    }

    public function getMonthBookAttribute() {
        return BookModel::where('cat_id', $this->id)->orderBy('click_count', 'desc')->limit(10)->all();
    }

    public function getWeekBookAttribute() {
        return BookModel::where('cat_id', $this->id)->orderBy('click_count', 'desc')->limit(10)->all();
    }

    public function getUrlAttribute() {
        return url('./category', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return url('./mobile/category', ['id' => $this->id]);
    }

    public function getRecommendBookAttribute() {
        return $this->recommend_book = BookModel::ofClassify()->where('cat_id', $this->id)->limit(4)->all();
    }

    public function getBestRecommendBookAttribute() {
        return $this->best_recommend_book = BookModel::ofClassify()->where('cat_id', $this->id)->limit(5)->all();
    }

    /**
     * 新建
     * @param $name
     * @return static
     */
    public static function findOrNewByName($name) {
        $name = trim($name);
        if (empty($name)) {
            return static::orderBy('id', 'asc')->first();
        }
        $model = static::where('name', $name)->one();
        if (!empty($model)) {
            return $model;
        }
        return static::create([
            'name' => $name
        ]);
    }

    public static function findIdByName($name) {
        return static::findOrNewByName($name)->id;
    }
}