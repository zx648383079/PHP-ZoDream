<?php
namespace Service\Admin;

use Domain\Model\Admin\MobileModel;
use Zodream\Domain\Response\Ajax;
use Zodream\Infrastructure\Request;

class MobileController extends Controller {
	protected $rules = array(
			'*'      => '*'
	);
	
	function indexAction() {
		$number = Request::getInstance()->get('phone');
		if (preg_match('/^(1[34578]\d{5})\d{4}$/', $number, $match)) {
			$model = new MobileModel();
			$data = $model->findNumber($match[1]);
			if (!empty($data)) {
				Ajax::ajaxReturn(array(
					'status' => 'success',
					'data' => $data
				));
			}
		}
		Ajax::ajaxReturn(array(
			'status' => 'failure',
			'error' => '请输入有效的号码'
		));
	}
}