<?php
namespace Domain\Form\Admin;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Admin\LogsModel;
class LogsForm extends Form {
	public function get($id) {
		$model = new LogsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('action,data,url,ip,cdate');
		if (!$this->validate($data, array(
			'action' => 'required',
			'data' => 'required',
			'url' => 'required',
			'ip' => 'required',
			'cdate' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new LogsModel();
		$model->add($data);
	}
}