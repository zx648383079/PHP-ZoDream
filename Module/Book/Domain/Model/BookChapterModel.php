<?php
namespace Module\Book\Domain\Model;

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
 * @property integer $id
 * @property string $title 章节名
 * @property string $content 章节内容
 * @property boolean $is_vip vip章节
 * @property float $price 章节价格
 * @property integer $book_id
 * @property integer $parent_id
 * @property integer $status
 * @property string $source
 * @property integer $size
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookChapterModel extends Model {
    public static function tableName() {
        return 'book_chapter';
    }

    protected function rules() {
        return [
            'book_id' => 'int',
            'title' => 'required|string:0,200',
            'parent_id' => 'int',
            'status' => 'int:0,9',
            'source' => 'string:0,200',
            'size' => 'int',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'book_id' => '书',
            'title' => '标题',
            'parent_id' => '上级',
            'status' => '状态',
            'source' => '来源',
            'size' => '字数',
            'deleted_at' => '删除时间',
            'created_at' => '发布时间',
            'updated_at' => '更新时间',
        ];
    }

    public function body() {
        return $this->hasOne(BookChapterBodyModel::class, 'id', 'id');
    }

    public function book() {
        return $this->hasOne(BookModel::class, 'id', 'book_id');
    }

    public function getUrlAttribute() {
        return url('./book/read', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return url('./mobile/book/read', ['id' => $this->id]);
    }

    public function getPreviousAttribute() {
        return static::where('id', '<', $this->id)->where('book_id', $this->book_id)
            ->orderBy('position', 'desc')
            ->orderBy('id desc')->select('id, title')->one();
    }

    public function getNextAttribute() {
        return static::where('id', '>', $this->id)
            ->where('book_id', $this->book_id)
            ->orderBy('position', 'asc')
            ->orderBy('id asc')
            ->select('id, title')->one();
    }

    public function save() {
        $is_new = $this->isNewRecord;
        if ($this->size < 1) {
            $this->size = mb_strlen($this->content);
        } else {
            $this->size = min($this->size, mb_strlen($this->content));
        }
        $row = parent::save();
        if (!$row) {
            return $row;
        }
        $model = new BookChapterBodyModel([
            'id' => $this->id,
            'content' => $this->content
        ]);
        $model->isNewRecord = $is_new;
        return $row && $model->save();
    }
}