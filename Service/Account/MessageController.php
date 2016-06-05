<?php
namespace Service\Account;

use Domain\Model\EmpireModel;
use Zodream\Domain\Authentication\Auth;

class MessageController extends Controller {

	function indexAction() {
		$data = EmpireModel::query('message m')->findAll(array(
			'left' => array(
				'user u',
				'm.send_id = u.id'
			),
			'where' => array(
				'm.user_id' => Auth::user()['id'],
				array(
					'm.user_id',
					-1,
					'or'
				)
			),
			'group' => 'm.send_id',
			'order' => 'm.read desc, m.create_at desc'
		), 'm.id as id, u.name as name, m.send_id as send_id, m.user_id as user_id, m.content as content, m.create_at as create_at');
		$this->show(array(
			'title' => '私信',
			'data' => $data
		));
	}

	function sendAction($id) {
		EmpireModel::query('message')->update(array(
			'send_id' => $id,
			'user_id' => Auth::user()['id']
		), 'read = 1');

		$data = EmpireModel::query('message')->findAll(array(
			'where' => array(
				'send_id' => $id,
				'(',
				'user_id' => Auth::user()['id'],
				array(
					'user_id',
					-1,
					'or'
				),
				')'
			),
			'order' => 'create_at desc'
		));
		$user = EmpireModel::query('user')->findById($id);
		$this->show(array(
			'title' => '与 '.$user['name'].' 的私信',
			'data' => $data,
			'user' => $user
		));
	}

	/**
	 * @param Post $post
	 */
	function sendPost($post) {
		$data = $post->get('content,user_id');
		if (empty($content) || empty($data['user_id'])) {
			return;
		}
		EmpireModel::query('message')->add(array(
			'send_id' => Auth::user()['id'],
			'content' => $content,
			'user_id' => intval($data['user_id']),
			'create_at' => time()
		));
	}
	
	function bulletinAction() {
		$data = EmpireModel::query('bulletin_user u')
			->findAll([
				'left' => [
					'bulletin b',
					'b.id = u.bulletin'
				],
				'where' => [
					'user_id' => Auth::user()['id']
				],
				'order' => 'u.read asc'
			], [
				'id' => 'b.id',
				'title' => 'b.title',
				'content' => 'b.content',
				'type' => 'b.type',
				'read' => 'u.read'
			]);
		$this->show([
			'title' => '消息管理',
			'data' => $data
		]);
	}
}