<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Zodream\Domain\Response\Redirect;

class BlogController extends Controller {
	function indexAction($search = null, $termid = null) {
		$term = EmpireModel::query('term')->find();
		$where = array();
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "p.title like '%{$item}%'";
			}
		}
		if (!empty($termid)) {
			$where[] = 'p.term_id = '.intval($termid);
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
			'title' => '博客',
			'page' => $data,
			'term' => $term
		));
	}

	function viewAction($id = null) {
		if (empty($id)) {
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
			'comment_status' => 'p.comment_status',
			'update_at' => 'p.update_at',
			'create_at' => 'p.create_at',
		));
		$this->show(array(
			'title' => $data['title'],
			'data' => $data,
			'links' => EmpireModel::query()->getNextAndBefore($id)
		));
	}
}