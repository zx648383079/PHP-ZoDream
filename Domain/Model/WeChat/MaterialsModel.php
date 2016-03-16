<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class MaterialsModel extends Model {
	protected $table = 'materials';
	
	protected $fillable = array(
		'account_id',
		'media_id',
		'original_id',
		'parent_id',
		'type',
		'is_multi',
		'can_edited',
		'title',
		'description',
		'author',
		'content',
		'cover_media_id',
		'cover_url',
		'show_cover_pic',
		'created_from',
		'source_url',
		'content_url',
		'created_at',
		'updated_at',
		'deleted_at'
	);
	
	public function findList($accountId, $type, $pageSize) {
		return $this->find(array(
			'where' => array(
				'type = '.$type,
				'account_id = '.$accountId,
				'parent_id = 0'
			),
			'order' => 'id desc',
			'limit' => $pageSize,
		));
	}
}