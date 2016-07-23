<?php
namespace Service\Home;


use Infrastructure\HtmlExpand;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Infrastructure\Request;

class ChatController extends Controller {
	protected function rules() {
		return array(
			'*' => '@'
		);
	}

	function indexAction($id) {
		$user = EmpireModel::query('user')->findById($id);
		$data = EmpireModel::query('chat')->findAll(array(
			'where' => array(
				'(user_id = '. Auth::user()['id'],
				'send_id = '.$id.')',
				['(send_id = '.Auth::user()['id'], 'or'],
				'user_id = '.$id.')'
			),
			'order' => 'create_at desc',
			'limit' => 10
		));
		$this->show(array(
			'title' => '与 '.$user['name'].' 的聊天室',
			'data' => $data,
			'user' => $user
		));
	}

	function sendAction() {
		$data = Request::post('user:user_id,content');
		$data['send_id'] = Auth::user()['id'];
		$data['create_at'] = time();
		$row = EmpireModel::query('chat')->add($data);
		if (empty($row)) {
			$this->ajaxReturn(array(
					'status' => 'failure',
					'error' => '服务器错误！'//EmpireModel::query()->getError()
			));
		}
		$this->ajaxReturn(array(
			'status' => 'success',
			'time' => TimeExpand::format($data['create_at'])
		));
	}

	function getAction($user, $time) {
		$data = EmpireModel::query('chat')->findAll(array(
			'where' => array(
				'user_id' => Auth::user()['id'],
				'send_id' => $user,
				'create_at > '.$time
			),
			'order' => 'create_at asc'
		), 'content,create_at');
		$this->ajaxReturn(array(
			'status' => 'success',
			'data' => $data,
			'time' => time()
		));
	}
}