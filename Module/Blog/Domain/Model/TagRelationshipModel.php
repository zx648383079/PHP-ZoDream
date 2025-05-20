<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;


/**
 * Class TagRelationshipModel
 * @package Module\Blog\Domain\Model
 * @property integer $tag_id
 * @property integer $target_id
 * @property integer $position
 */
class TagRelationshipModel extends Model {

    protected string $primaryKey = '';

	public static function tableName(): string {
        return 'blog_tag_link';
    }

    protected function rules(): array {
        return [
            'tag_id' => 'required|int',
            'target_id' => 'required|int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels(): array {
        return [
            'tag_id' => 'Tag Id',
            'target_id' => 'Blog Id',
            'position' => 'Position',
        ];
    }

}