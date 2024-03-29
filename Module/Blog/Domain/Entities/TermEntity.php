<?php
namespace Module\Blog\Domain\Entities;

use Domain\Entities\Entity;


/**
 * Class TermEntity
 * @property integer $id
 * @property string $name
 * @property string $keywords
 * @property string $description
 * @property integer $user_id
 * @property integer $parent_id
 * @property string $thumb
 * @property string $styles
 */
class TermEntity extends Entity {
	public static function tableName(): string {
        return 'blog_term';
    }

	public function rules(): array {
        return [
            'name' => 'required|string:1,200',
            'keywords' => 'string:0,200',
            'description' => 'string:0,200',
            'user_id' => 'int',
            'parent_id' => 'int',
            'thumb' => '',
            'styles' => '',
            'en_name' => 'string:0,40',
        ];
	}

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '分类',
            'keywords' => '关键字',
            'description' => '说明',
            'user_id' => 'User Id',
            'parent_id' => '上级',
            'thumb' => '图片',
            'styles' => '样式'
        ];
    }

}