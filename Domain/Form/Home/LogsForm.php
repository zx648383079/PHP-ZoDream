<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\LogsModel;
class LogsForm extends Form {
	public function get($id) {
		$model = new LogsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('action,data,url,ip,create_at');
		if (!$this->validate($data, array(
			'action' => 'required',
			'data' => 'required',
			'url' => 'required',
			'ip' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new LogsModel();
		$model->add($data);
	}
}