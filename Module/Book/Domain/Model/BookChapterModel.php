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
 * Class BookChapterModel
 * @package Domain\Model\Book
 * @property integer $id
 * @property string $title 章节名
 * @property string $content 章节内容
 * @property integer $words_count 字数
 * @property boolean $is_vip vip章节
 * @property float $price 章节价格
 * @property integer $book_id
 * @property integer $parent_id
 * @property integer $status
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
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'book_id' => 'Book Id',
            'title' => 'Title',
            'parent_id' => 'Parent Id',
            'status' => 'Status',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function body() {
        return $this->hasOne(BookChapterBodyModel::class, 'id', 'id');
    }

    public function getUrlAttribute() {
        return Url::to('./home/detail', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return Url::to('./wap/detail', ['id' => $this->id]);
    }

    public function save() {
        $is_new = $this->isNewRecord;
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