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
 */
class BookPageModel extends BookModel {

    protected array $append = ['category', 'author'];
}