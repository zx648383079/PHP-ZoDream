<?php
declare(strict_types=1);
namespace Module\Note\Domain\Entities;

use Domain\Entities\Entity;
use Module\Auth\Domain\Model\UserSimpleModel;
/**
 * 
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $user_id
 * @property integer $status
 * @property integer $created_at
 */
class TopicEntity extends Entity {
    public static function tableName(): string {
        return 'note_topic';
    }

    protected function rules(): array {
		return [
            'title' => 'required|string:0,200',
            'description' => 'string:0,255',
            'user_id' => 'int',
            'status' => 'int:0,127',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'title' => 'Title',
            'description' => 'Description',
            'user_id' => 'User Id',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
	}


    public function user() {
	    return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}