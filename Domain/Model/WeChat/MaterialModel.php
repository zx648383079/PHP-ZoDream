<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
class MaterialModel extends Model {
	protected $table = 'wechat_material';
	
	protected $fillAble = array(
		'wechat_id',
		'media_id',
		'original_id',
		'parent',
		'type',
		'is_mutli',
		'can_edit',
		'title',
		'descrption',
		'author',
		'content',
		'cover_media_id',
		'cover_url',
		'show_cover',
		'create_from',
		'source_url',
		'content_url',
		'create_at',
		'update_at'
	);
}