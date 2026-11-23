<?php
namespace Module\Book\Domain\Model;


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
 */
class BookPageModel extends BookModel {

    protected array $append = ['category', 'author', 'status_label'];
}