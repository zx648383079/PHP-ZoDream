<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request\Post;

class BlogController extends Controller {
	function indexAction($search = null, $termid = null, $user = null) {
		$term = EmpireModel::query('term')->find();
		$where = array();
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "p.title like '%{$item}%'";
			}
		}
		if (!empty($termid)) {
			$where[] = 'tp.term_id = '.intval($termid);
		}
		if (!empty($user)) {
			$where[] = 'p.user_id = '.intval($user);
		}
		$data = EmpireModel::query('post p')->getPage(array(
			'left' => array(
				'user u',
				'p.user_id = u.id',
				'term_post tp',
				'p.id = tp.post_id',
				'term t',
				'tp.term_id = t.id'
			),
			'where' => $where,
			'order' => 'create_at desc'
		), array(
			'id' => 'p.id',
			'title' => 'p.title',
			'user' => 'u.name',
			'user_id' => 'p.user_id',
			'term' => 't.name',
			'comment_count' => 'p.comment_count',
			'create_at' => 'p.create_at',
			'excerpt' => 'p.excerpt',
			'recommend' => 'p.recommend',
			'comment_count' => 'p.comment_count'
		),
		array(
			'where' => $where,
		));
		$this->show(array(
			'title' => '博客',
			'page' => $data,
			'term' => $term
		));
	}

	function viewAction($id = null) {
		$id = intval($id);
		if ($id < 1) {
			Redirect::to('blog');
		}
		$data = EmpireModel::query('post p')->findOne(array(
			'left' => array(
				'user u',
				'p.user_id = u.id',
				'term_post tp',
				'p.id = tp.post_id',
				'term t',
				'tp.term_id = t.id'
			),
			'where' => array(
				'p.id = '.intval($id)
			)
		), array(
			'id' => 'p.id',
			'title' => 'p.title',
			'content' => 'p.content',
			'term' => 't.name',
			'user' => 'u.name',
			'user_id' => 'p.user_id',
			'comment_status' => 'p.comment_status',
			'comment_count' => 'p.comment_count',
			'update_at' => 'p.update_at',
			'create_at' => 'p.create_at',
			'recommend' => 'p.recommend',
		));
		$comment = EmpireModel::query('comment')->find(array(
			'where' => array(
				'post_id = '.$id
			),
			'order' => 'create_at'
		));
		$this->show(array(
			'title' => $data['title'],
			'data' => $data,
			'links' => EmpireModel::query()->getNextAndBefore($id),
			'comment' => $comment
		));
	}

	public function recommendAction($id) {
		$result = EmpireModel::query('post')->updateOne('recommend', 'id = '. intval($id));
		$this->ajaxReturn(array(
			'status' => empty($result) ? 'error' : 'success'
		));
	}

	/**
	 * @param Post $post
	 */
	function viewPost($post) {
		if (!Auth::guest()) {
			$post->set(array(
				'name' => Auth::user()['name'],
				'email' => Auth::user()['email'],
				'url' => ''
			));
		}
		$id = EmpireModel::query('comment')->save(array(
			'post_id' => 'required|int',
			'parent' => 'int',
			'name' => 'required|string:1-45',
			'email' => 'required|email',
			'url' => 'string:-100',
			'content' => 'required',
			'create_at' => '',
			'user_id' => ''
		), $post->get());
		if (empty($id)) {
			return;
		}
		EmpireModel::query('post')->updateOne(
			'comment_count',
			'id = '. intval($post->get('post_id')));
	}
}