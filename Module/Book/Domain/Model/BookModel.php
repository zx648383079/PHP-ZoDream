<?php
namespace Module\Book\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/10/15
 * Time: 21:00
 */
use Domain\Model\Model;
use Module\Book\Domain\Entities\BookEntity;
use Module\Book\Domain\Repositories\BookRepository;
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
 * @property integer $status
 * @property string $source
 * @property integer $click_count
 * @property integer $recommend_count
 * @property integer $over_at
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 * @method static Query ofClassify()
 * @method static Query isOpen()
 */
class BookModel extends BookEntity {

    protected array $append = ['status_label'];

    public $classify_list = [
        '无分级',
        18 => '成人级',
    ];

    public function chapter() {
        return $this->hasMany(BookChapterModel::className(), 'book_id', 'id');
    }

    public function category() {
        return $this->hasOne(BookCategoryModel::className(), 'id', 'cat_id');
    }

    public function author() {
        return $this->hasOne(BookAuthorModel::className(), 'id', 'author_id');
    }

    public function getCoverAttribute() {
        $cover = $this->getAttributeSource('cover');
        if (empty($cover)) {
            $cover = BookRepository::DEFAULT_COVER;
        }
        return url()->asset($cover);
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

    public function scopeIsOpen($query) {
        $query->where('status', 1);
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

    public function getStatusLabelAttribute() {
        $status = $this->getAttributeSource('status');
        if ($status < 1) {
            return '审核中';
        }
        if ($status == 9) {
            return '已屏蔽';
        }
        return $this->over_at > 0 ? '已完本' : '连载中';
    }

    /**
     * @return BookChapterModel
     */
    public function getLastChapterAttribute() {
        return BookChapterModel::where('book_id', $this->id)->orderBy('created_at', 'desc')->one();
    }

    public function getChapterCountAttribute() {
        return BookChapterModel::where('book_id', $this->id)->count();
    }

    public function getLastAtAttribute() {
        return Time::format($this->getAttributeSource('updated_at'), 'm-d H:i');
    }

    public function getFormatSizeAttribute() {
        if ($this->getAttributeSource('size') > 10000) {
            return sprintf('%.2f万字', $this->getAttributeSource('size') / 10000);
        }
        return sprintf('%s字', $this->getAttributeSource('size'));
    }

    public function getMonthClickAttribute() {
        return ceil($this->click_count / 12);
    }

    public function getWeekClickAttribute() {
        return ceil($this->click_count / 40);
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