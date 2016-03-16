<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class RepliesModel extends Model {
	protected $table = 'replies';
	
	protected $fillable = array(
		'account_id',
		'type',
		'name',
		'trigger_keywords',
		'trigger_type',
		'content',
		'group_ids',
		'created_at',
		'updated_at',
		'deleted_at'
	);

	/**
	 * 类型关注回复.
	 */
	const TYPE_FOLLOW = 'follow';

	/**
	 * 类型无匹配时回复.
	 */
	const TYPE_NO_MATCH = 'no-match';

	/**
	 * 类型关键词回复.
	 */
	const TYPE_KEYWORDS = 'keywords';

	const TRIGGER_TYPE_EQUAL = 'equal';

	const TRIGGER_TYPE_CONTAIN = 'contain';
	
	public function findList($accountId, $page = 1) {
		return $this->find(array(
			'where' => array(
				'type = '.self::TYPE_KEYWORDS,
				'account_id = '. $accountId
			)
		));
	}
}