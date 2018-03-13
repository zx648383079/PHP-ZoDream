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
 * @property integer $author_id 作者
 * @property integer $user_id
 * @property integer $cat_id
 * @property integer $size
 * @property integer $click_count
 * @property integer $recommend_count
 * @property integer $over_at
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookModel extends Model {
    public static function tableName() {
        return 'book';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'cover' => 'required|string:0,200',
            'description' => 'required|string:0,200',
            'author_id' => 'required|int',
            'user_id' => 'int',
            'cat_id' => 'int:0,999',
            'size' => 'int',
            'click_count' => 'int',
            'recommend_count' => 'int',
            'over_at' => 'int',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'cover' => 'Cover',
            'description' => 'Description',
            'author_id' => 'Author Id',
            'user_id' => 'User Id',
            'cat_id' => 'Cat Id',
            'size' => 'Size',
            'click_count' => 'Click Count',
            'recommend_count' => 'Recommend Count',
            'over_at' => 'Over At',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getChapters() {
        return $this->hasMany(BookChapterModel::className(), 'book_id', 'id');
    }

    public function category() {
        return $this->hasOne(BookCategoryModel::className(), 'id', 'cat_id');
    }

    public function author() {
        return $this->hasOne(BookAuthorModel::className(), 'id', 'author_id');
    }

    public function getCoverAttribute() {
        $cover = $this->getAttributeValue('cover');
        if (!empty($cover)) {
            return $cover;
        }
        return '/assets/images/book_default.jpg';
    }

    public function getUrlAttribute() {
        return Url::to('./book', ['id' => $this->id]);
    }

    public function getDownloadUrlAttribute() {
        return Url::to('./book/download', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return Url::to('./mobile/book', ['id' => $this->id]);
    }

    public function getFirstChapterAttribute() {
        return BookChapterModel::where('book_id', $this->id)->order('id', 'asc')->one();
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