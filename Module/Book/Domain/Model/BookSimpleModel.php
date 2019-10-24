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
use Zodream\Database\Model\Query;
use Zodream\Helpers\Time;


/**
 * Class BookModel
 * @package Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $cover 封面
 * @property string $description 简介
 * @property integer $words_count 字数
 * @property integer $over_at
 * @property integer $updated_at
 */
class BookSimpleModel extends BookEntity {

    public function getCoverAttribute() {
        $cover = $this->getAttributeSource('cover');
        if (empty($cover)) {
            $cover = '/assets/images/book_default.jpg';
        }
        return url()->asset($cover);
    }

    public static function query() {
        return parent::query()->select([
            'id', 'name', 'cover', 'description', 'words_count',
            'over_at', 'updated_at']);
    }
}