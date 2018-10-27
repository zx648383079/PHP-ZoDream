<?php
namespace Module\Book\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/10/15
 * Time: 21:00
 */
use Domain\Model\Model;
use Zodream\Database\Model\Query;
use Zodream\Helpers\Time;


/**
 * Class BookModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $name
 * @property string $cover 封面
 * @property string $description 简介
 * @property integer $words_count 字数
 * @property integer $author_id 作者
 * @property integer $user_id
 * @property integer $classify
 * @property integer $cat_id
 * @property integer $size
 * @property string $source
 * @property integer $click_count
 * @property integer $recommend_count
 * @property integer $over_at
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 * @method Query ofClassify()
 */
class BookModel extends Model {

    public $classify_list = [
        '无分级',
        '成人级',
    ];

    public static function tableName() {
        return 'book';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'cover' => 'string:0,200',
            'description' => 'string:0,200',
            'author_id' => 'int',
            'user_id' => 'int',
            'classify' => 'int',
            'cat_id' => 'int:0,999',
            'size' => 'int',
            'source' => 'string:0,200',
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
            'name' => '书名',
            'cover' => '封面',
            'description' => '简介',
            'author_id' => '作者',
            'user_id' => 'User Id',
            'classify' => '分级',
            'cat_id' => '分类',
            'size' => '字数',
            'source' => '来源',
            'click_count' => '点击',
            'recommend_count' => '推荐',
            'over_at' => '完本时间',
            'deleted_at' => '删除时间',
            'created_at' => '发布时间',
            'updated_at' => '更新时间',
        ];
    }

    public function chapter() {
        return $this->hasMany(BookChapterModel::className(), 'book_id', 'id');
    }

    public function category() {
        return $this->hasOne(BookCategoryModel::className(), 'id', 'cat_id');
    }

    public function author() {
        return $this->hasOne(BookAuthorModel::className(), 'id', 'author_id');
    }

    /**
     * 小说分级，未登录自动屏蔽有分级的
     * @param $query
     */
    public function scopeOfClassify($query) {
        if (auth()->guest()) {
            $query->where('classify', 0);
        }
    }

    public function getCoverAttribute() {
        $cover = $this->getAttributeValue('cover');
        if (!empty($cover)) {
            return $cover;
        }
        return '/assets/images/book_default.jpg';
    }

    public function getUrlAttribute() {
        return url('./book', ['id' => $this->id]);
    }

    public function getDownloadUrlAttribute() {
        return url('./book/download', ['id' => $this->id]);
    }

    public function getWapDownloadUrlAttribute() {
        return url('./mobile/book/download', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return url('./mobile/book', ['id' => $this->id]);
    }

    public function getFirstChapterAttribute() {
        return BookChapterModel::where('book_id', $this->id)->orderBy('id', 'asc')->one();
    }

    public function getStatusAttribute() {
        return $this->over_at > 0 ? '已完本' : '连载中';
    }

    /**
     * @return BookChapterModel
     */
    public function getLastChapterAttribute() {
        return BookChapterModel::where('book_id', $this->id)->orderBy('created_at', 'desc')->one();
    }

    public function getLastAtAttribute() {
        return Time::format($this->__attributes['updated_at'], 'm-d H:i');
    }

    /**
     * 判断小说是否已存在
     * @return bool
     */
    public function isExist() {
        return static::where('name', $this->name)
            ->where('id', '<>', $this->id)->count() > 0;
    }
}