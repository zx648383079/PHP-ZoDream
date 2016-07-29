<?php
namespace Service\Home;


use Domain\Model\Message\ChatModel;
use Zodream\Domain\Access\Auth;
use Zodream\Domain\Model\UserModel;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Infrastructure\Request;

class ChatController extends Controller {
	protected function rules() {
		return array(
			'*' => '@'
		);
	}

	function indexAction($id) {
		$user = UserModel::findOne($id);
		$data = ChatModel::findAll(array(
			'where' => array(
				'(user_id = '. Auth::user()['id'],
				'send_id = '.$id.')',
				['(send_id = '.Auth::user()['id'], 'or'],
				'user_id = '.$id.')'
			),
			'order' => 'create_at desc',
			'limit' => 10
		));
		return $this->show(array(
			'title' => '与 '.$user['name'].' 的聊天室',
			'data' => $data,
			'user' => $user
		));
	}

	function sendAction() {
		$data = Request::post('user:user_id,content');
		$model = new ChatModel();
		$model->set($data);
		$model->send_id = Auth::user()['id'];
		$model->create_at = time();
		if (!$model->save()) {
			return $this->ajax(array(
				'status' => 'failure',
				'error' => '服务器错误！'//EmpireModel::query()->getError()
			));
		}
		return $this->ajax(array(
			'status' => 'success',
			'time' => TimeExpand::format($data['create_at'])
		));
	}

	function getAction($user, $time) {
		$data = ChatModel::findAll(array(
			'where' => array(
				'user_id' => Auth::user()['id'],
				'send_id' => $user,
				'create_at > '.$time
			),
			'order' => 'create_at asc'
		), 'content,create_at');
		return $this->ajax(array(
			'status' => 'success',
			'data' => $data,
			'time' => time()
		));
	}
}