<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\MigrationsModel;
class MigrationsForm extends Form {
	public function get($id) {
		$model = new MigrationsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('migration,batch');
		if (!$this->validate($data, array(
			'migration' => 'required',
			'batch' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new MigrationsModel();
		$model->add($data);
	}
}