<?php
namespace Service\Home;

/**
 * 论坛版块
 */

use Domain\Model\Forum\ForumModel;
use Domain\Model\Forum\ThreadModel;
use Domain\Model\Forum\ThreadPostModel;
use Zodream\Domain\Access\Auth;
use Zodream\Html\Page;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Http\Request;

class ForumController extends Controller {
	
	protected function rules() {
		return array(
			'add' => '@'
		);
	}

	public function prepare() {
		parent::prepare();
		$search = Request::get('search');
		if (empty($search)) {
			return;
		}
		$searches = explode(' ', $search);
		$where = array();
		foreach ($searches as $item) {
			$where[] = "title like '%{$item}%'";
		}
		$page = new Page(ThreadModel::find()->where($where));
		$page->setPage(ThreadModel::find()->alias('t')->load(array(
			'left' => array(
				'user u',
				't.update_user = u.id'
			),
			'where' => $where,
			'order' => 'create_at desc'
		))->select(array(
			'id' => 't.id',
			'update_at' => 't.update_at',
			'update_user' => 'u.name',
			'title' => 't.title',
			'user_name' => 't.user_name',
			'create_at' => 't.create_at',
			'replies' => 't.replies',
			'views' => 't.views'
		)));
		$this->show('forum.search', array(
			'page' => $page,
			'title' => '搜 '.$search
		));
	}

	function indexAction() {
		$data = ForumModel::findAll(array(
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
		return $this->show(array(
			'title' => '论坛',
			'data' => $data
		));
	}

	function threadAction($id = null) {
		$id = intval($id);
		if ($id < 1) {
			return $this->redirect('forum');
		}
		$sub = ForumModel::findAll(array(
			'where' => array(
				'type' => 'sub',
				'parent = '. $id
			)
		));
		$page = new Page(ThreadModel::find()->where('forum_id = '.$id));
		$page->setPage(ThreadModel::find()->load(array(
			'left' => array(
				'user u',
				't.update_user = u.id'
			),
			'where' => array(
				't.forum_id = '.$id
			),
			'order' => 'create_at desc',
				'select' => array(
				'id' => 't.id',
				'update_at' => 't.update_at',
				'update_user' => 'u.name',
				'title' => 't.title',
				'user_name' => 't.user_name',
				'create_at' => 't.create_at',
				'replies' => 't.replies',
				'views' => 't.views'
			))));
		return $this->show(array(
			'sub' => $sub,
			'page' => $page,
			'id' => $id
		));
	}

	function addAction($id) {
		return $this->show(array(
			'id' => $id
		));
	}

	/**
	 * @param Post $post
	 */
	function addPost($post) {
		$this->threadPost($post);
		Redirect::to('forum/thread/id/'.$post->get('forum_id'), 2, '发表成功！');
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
			return $this->redirect('forum');
		}
		$model = ThreadModel::findOne($id);
		$model->views ++;
		$model->update();
		$page = new Page(ThreadPostModel::find()->where('thread_id = '.$id));
		$page->setPage(ThreadPostModel::find()->alias('p')->load(array(
			'left' => [
				'user u',
				'p.user_id = u.id'
			],
			'where' => array(
				'p.thread_id = '.$id
			),
			'order' => 'p.first desc,p.create_at asc'
		))->select(array(
			'user' => 'p.user_id',
			'`name`' => 'u.name',
			'avatar' => 'u.avatar',
			'content' => 'p.content',
			'create_at' => 'p.create_at',
			'first' => 'p.first'
		)));
		$this->show(array(
			'title' => $model->title,
			'page' => $page,
			'data' => $model
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