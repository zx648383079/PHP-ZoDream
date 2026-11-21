<?php
namespace Module\Note\Domain\Entities;

use Domain\Entities\Entity;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $topic_id
 * @property string $extra_rule
 * @property string $properties
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class NoteEntity extends Entity {

	public static function tableName(): string {
        return 'note';
    }

	protected function rules(): array {
		return [
            'content' => 'required|string:0,255',
            'topic_id' => 'int',
            'status' => 'int:0,127',
            'extra_rule' => 'string:0,255',
            'properties' => 'string:0,100',
            'user_id' => 'required|int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'content' => 'Content',
            'user_id' => 'User Id',
            'topic_id' => 'Topic Id',
            'status' => 'Status',
            'created_at' => 'Created At',
            'extra_rule' => 'Extra Rule',
            'properties' => 'Properties',
            'updated_at' => 'Updated At',
        ];
	}

    public function user() {
	    return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function topic() {
        return $this->hasOne(TopicEntity::class, 'id', 'topic_id');
    }
}