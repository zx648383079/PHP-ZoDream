<?php
namespace Module\Book\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/10/15
 * Time: 21:00
 */
use Domain\Model\Model;
use Module\Book\Domain\Entities\ChapterEntity;
use Module\Book\Domain\Repositories\ChapterRepository;


/**
 * Class BookChapterModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $title 章节名
 * @property string $content 章节内容
 * @property boolean $is_vip vip章节
 * @property float $price 章节价格
 * @property integer $book_id
 * @property integer $type
 * @property integer $parent_id
 * @property integer $status
 * @property integer $position
 * @property integer $size
 * @property integer $deleted_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class BookChapterModel extends ChapterEntity {


    public function body() {
        return $this->hasOne(BookChapterBodyModel::class, 'id', 'id');
    }

    public function book() {
        return $this->hasOne(BookSimpleModel::class, 'id', 'book_id');
    }

    public function getIsBoughtAttribute() {
        return ChapterRepository::isBought($this->book_id, $this->id, $this->type);
    }

    public function getUrlAttribute() {
        return url('./book/read', ['id' => $this->id]);
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

    public function save(bool $force = false): mixed {
        $is_new = $this->isNewRecord;
        if ($this->size < 1) {
            $this->size = mb_strlen($this->content);
        } else {
            $this->size = min($this->size, mb_strlen($this->content));
        }
        $row = parent::save(true);
        if (!$row) {
            return $row;
        }
        if ($this->type == ChapterRepository::TYPE_GROUP) {
            return true;
        }
        $model = new BookChapterBodyModel([
            'id' => $this->id,
            'content' => $this->content
        ]);
        $model->isNewRecord = $is_new;
        if ($model->save(true)) {
            return true;
        }
        if (!$is_new) {
            return true;
        }
        $model->setError('content', $model->getFirstError());
        return false;
    }
}