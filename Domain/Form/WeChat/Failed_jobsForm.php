<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\Failed_jobsModel;
class Failed_jobsForm extends Form {
	public function get($id) {
		$model = new Failed_jobsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('connection,queue,payload,failed_at');
		if (!$this->validate($data, array(
			'connection' => 'required',
			'queue' => 'required',
			'payload' => 'required',
			'failed_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new Failed_jobsModel();
		$model->add($data);
	}
}