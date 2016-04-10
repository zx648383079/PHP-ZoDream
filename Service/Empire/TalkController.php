<?php
namespace Service\Empire;

use Domain\Model\EmpireModel;

class TalkController extends Controller {
	function indexAction() {
		$data = EmpireModel::query('talk')->find(array(
			'order' => 'create_at desc'
		));
		$this->show(array(
			'title' => '随想',
			'data' => $data
		));
	}
}