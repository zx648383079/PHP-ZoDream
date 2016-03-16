<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\FansModel;
class FansForm extends Form {
	public function get($id) {
		$model = new FansModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,group_id,openid,nickname,signature,remark,sex,language,city,province,country,avatar,unionid,liveness,subscribed_at,last_online_at,created_at,updated_at,deleted_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'group_id' => 'required',
			'openid' => 'required',
			'nickname' => 'required',
			'signature' => 'required',
			'remark' => 'required',
			'sex' => 'required',
			'language' => 'required',
			'city' => 'required',
			'province' => 'required',
			'country' => 'required',
			'avatar' => 'required',
			'unionid' => 'required',
			'liveness' => 'required',
			'subscribed_at' => 'required',
			'last_online_at' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required',
			'deleted_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new FansModel();
		$model->add($data);
	}
}