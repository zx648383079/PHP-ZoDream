<?php
namespace Service\Admin;

/**
 * 博客
 */
use Domain\Model\EmpireModel;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\ObjectExpand\PinYin;
use Zodream\Infrastructure\Request\Post;

class PostController extends Controller {

	function indexAction($search = null) {
		$where = array();
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "p.title like '%{$item}%'";
			}
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
			'order' => 'create_at'
		), array(
			'id' => 'p.id',
			'title' => 'p.title',
			'user' => 'u.name',
			'term' => 't.name',
			'comment_count' => 'p.comment_count',
			'create_at' => 'p.create_at',
		),
		array(
			'where' => $where,
		));

		$this->show(array(
			'page' => $data
		));
	}

	function addAction($id = null) {
		$term = EmpireModel::query('term')->find();
		if (!empty($id)) {
			$this->send('data', EmpireModel::query('post')->findById($id));
		}
		$this->show(array(
			'term' => $term,
			'id' => $id
		));
	}

	/**
	 * @param Post $post
	 *
	 */
	function addPost($post) {
		if ($post->get('status') !== 'password') {
			$post->set('password', null);
		}
		$id = EmpireModel::query('post')->save(array(
			'id' => '',
			'title' => 'required|string:1-100',
			'content' => 'required',
			'status' => 'required|enum:publish,password,private,draft',
			'password' => '',
			'create_at' => '',
			'user_id' => ''
		), $post->get());
		if (empty($id)) {
			return;
		}
		if (!empty($post->get('id'))) {
			$id = intval($post->get('id'));
		}
		EmpireModel::query('term_post')->insertOrUpdate(
			'post_id, term_id', ':post, :term', 'term_id = :term', array(
			':post' => $id,
			':term' => intval($post->get('term_id'))
		));
		Redirect::to('post');
	}

	function termAction() {
		$data = EmpireModel::query('term')->find();
		$this->show(array(
			'data' => $data
		));
	}

	/**
	 * @param Post $post
	 */
	function termPost($post) {
		$data = $post->get('id,name,slug');
		if (empty($data['slug'])) {
			$data['slug'] = PinYin::encode($data['name'], 'all');
		}
		EmpireModel::query('term')->save(array(
			'id' => '',
			'name' => 'required|string:1-100',
			'slug' => 'required|string:1-100'
		), $data);
	}

	function commentAction() {
		$data = EmpireModel::query('comment')->getPage();
		$this->show(array(
			'page' => $data
		));
	}
}