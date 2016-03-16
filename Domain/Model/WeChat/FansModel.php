<?php
namespace Domain\Model\WeChat;

use Zodream\Domain\Model;
class FansModel extends Model {
	protected $table = 'fans';
	
	protected $fillable = array(
		'account_id',
		'group_id',
		'openid',
		'nickname',
		'signature',
		'remark',
		'sex',
		'language',
		'city',
		'province',
		'country',
		'avatar',
		'unionid',
		'liveness',
		'subscribed_at',
		'last_online_at',
		'created_at',
		'updated_at',
		'deleted_at'
	);
	
	public function findPage($accountId, $searchName = '',  $groupId = 0, $orderBy = 'id', $page = 1, $pageSize = 21) {
		return $this->find(array(
			'where' => array(
				'account_id = '.$accountId,
				'group_id = '.$groupId,
				"nickname LIKE '%{$searchName}%'"
			),
			'order' => $orderBy.' desc',
			'limit' => (($page-1) * $pageSize) .',',$pageSize
		));
	}
}