<?php
namespace Service\Home;

/**
 * 论坛版块
 */
use Domain\Model\EmpireModel;
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request;

class ForumController extends Controller {
	protected function rules() {
		return array(
			'add' => '@'
		);
	}

	function indexAction() {
		$data = EmpireModel::query('forum')->find(array(
			'where' => array(
				'type' => array(
					'in', 
					array(
						'group',
						'forum'
					)
				)
			),
			'order' => 'parent'
		));
		$this->show(array(
			'title' => '论坛',
			'data' => $data
		));
	}

	function threadAction($id = null) {
		$id = intval($id);
		if ($id < 1) {
			Redirect::to('forum');
		}
		$sub = EmpireModel::query('forum')->find(array(
			'where' => array(
				'type' => 'sub',
				'parent = '. $id
			)
		));
		$page = EmpireModel::query('thread')->getPage(array(
			'where' => array(
				'forum_id = '.$id
			),
			'order' => 'create_at desc'
		));
		$this->show(array(
			'sub' => $sub,
			'page' => $page,
			'id' => $id
		));
	}

	function addAction() {
		$this->show();
	}

	/**
	 * @param Post $post
	 */
	function threadPost($post) {
		if (Auth::guest() || empty($post->get('content'))) {
			return;
		}
		$post->set(array(
			'user_name' => Auth::user()['name']
		));
		$id = EmpireModel::query('thread')->save(array(
			'forum_id' => 'required|int',
			'title' => 'required|string:1-200',
			'user_id' => '',
			'user_name' => '',
			'create_at' => ''
		));
		if (empty($id)) {
			return;
		}
		$post->set(array(
			'thread_id' => $id,
			'first' => true
		));
		EmpireModel::query('thread_post')->save(array(
			'forum_id' => '',
			'content' => '',
			'ip' => '',
			'first' => '',
			'thread_id' => '',
			'user_id' => '',
			'user_name' => '',
			'create_at' => ''
		), $post->get());
	}

	function postAction($id = null) {
		$id = intval($id);
		if ($id < 1) {
			Redirect::to('forum');
		}
		if (Request::isGet()) {
			EmpireModel::query('thread')->updateOne('views', 'id = '.$id);
		}
		$data = EmpireModel::query('thread')->findById($id);
		$page = EmpireModel::query('thread_post')->getPage(array(
			'where' => array(
				'thread_id = '.$id
			),
			'order' => 'first desc,create_at'
		));
		$this->show(array(
			'title' => $data['title'],
			'page' => $page,
			'data' => $data
		));
	}

	/**
	 * @param Post $post
	 */
	function postPost($post) {
		if (Auth::guest()) {
			return;
		}
		$post->set(array(
			'user_name' => Auth::user()['name']
		));
		$id = EmpireModel::query('thread_post')->save(array(
			'forum_id' => 'required|int',
			'content' => 'required',
			'ip' => '',
			'thread_id' => 'required|int',
			'user_id' => '',
			'user_name' => '',
			'create_at' => ''
		), $post->get());
		if (empty($id)) {
			return;
		}
		EmpireModel::query('thread')->updateById($post->get('thread_id'), array(
			'update_at' => time(),
			'replies = replies + 1',
			'update_user' => Auth::user()['id']
		));
	}
}