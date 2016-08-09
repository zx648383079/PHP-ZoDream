<?php
namespace Service\Account;


use Domain\Model\Message\BulletinUserModel;
use Domain\Model\Message\MessageModel;
use Zodream\Domain\Access\Auth;
use Zodream\Domain\Model\UserModel;

class MessageController extends Controller {

	function indexAction() {
		$data = MessageModel::find()->alias('m')->load(array(
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
		))
			->select('m.id as id, u.name as name, m.send_id as send_id, m.user_id as user_id, m.content as content, m.create_at as create_at')
		->all();
		return $this->show(array(
			'title' => '私信',
			'data' => $data
		));
	}

	function sendAction($id) {
		(new MessageModel())->update(array(
			'send_id' => $id,
			'user_id' => Auth::user()['id']
		), ['read' => true]);

		$data = MessageModel::find()->alias('m')->load(array(
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
		))->all();
		$user = UserModel::findOne($id);
		return $this->show(array(
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
		$data = BulletinUserModel::find()->alias('u')->load([
				'left' => [
					'bulletin b',
					'b.id = u.bulletin'
				],
				'where' => [
					'user_id' => Auth::user()['id']
				],
				'order' => 'u.read asc'
			])->select([
				'id' => 'b.id',
				'title' => 'b.title',
				'content' => 'b.content',
				'type' => 'b.type',
				'read' => 'u.read'
			])->all();
		return $this->show([
			'title' => '消息管理',
			'data' => $data
		]);
	}
}