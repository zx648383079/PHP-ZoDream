<?php
namespace Service\Home;

/**
 * 问答版块
 */

use Zodream\Domain\Access\Auth;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Html;
use Zodream\Infrastructure\Request;

class QuestionController extends Controller {
	
	protected function rules() {
		return array(
			'add' => '@'
		);
	}

	function indexAction() {
		$page = EmpireModel::query('question q')->getPage(array(
			'left' => [
				'user u',
				'u.id = q.user_id'
			],
			'order' => 'status asc,create_at desc'
		), [
			'id' => 'q.id',
			'user' => 'u.name',
			'user_id' => 'q.user_id',
			'title' => 'q.title',
			'count' => 'q.count',
			'create_at' => 'q.create_at',
			'status' => 'q.status'
		], []);
		$this->show(array(
			'title' => '问答',
			'page' => $page
		));
	}

	function addAction() {
		$this->show([
			'title' => '我要提问'
		]);
	}

	/**
	 * @param Request\Post $post
	 */
	function addPost($post) {
		$row = EmpireModel::query('question')->save([
			'title' => 'required|string:1-200',
			'content' => '',
			'user_id' => '',
			'create_at' => ''
		], $post->get());
		if (empty($row)) {
			$this->send($post->get());
		}
		Redirect::to(['question']);
	}


	function viewAction($id) {
		$data = EmpireModel::query('question q')->find()
			->left('user u', 'q.user_id = u.id')->where(['q.id' => $id])->select([
				'id' => 'q.id',
				'user' => 'u.name',
				'user_id' => 'q.user_id',
				'title' => 'q.title',
				'count' => 'q.count',
				'create_at' => 'q.create_at',
				'status' => 'q.status'
			])->one();
		$page = EmpireModel::query('question_answer qa')->getPage(array(
			'left' => [
				'user u',
				'qa.user_id = u.id'
			],
			'where' => array(
				'qa.question_id' => $id
			),
			'order' => 'qa.status desc,qa.create_at asc'
		), array(
			'user_id' => 'qa.user_id',
			'user' => 'u.name',
			'content' => 'qa.content',
			'create_at' => 'qa.create_at',
			'status' => 'qa.status',
			'parent_id' => 'qa.parent_id'
		), array(
			'where' => array(
				'question_id' => $id
			)
		));
		$this->show(array(
			'title' => $data['title'],
			'page' => $page,
			'data' => $data
		));
	}

	/**
	 * @param Request\Post $post
	 */
	function viewPost($post) {
		if (Auth::guest()) {
			return;
		}
		$question = EmpireModel::query('question')->findById($post->get('question_id'));
		if (empty($question)) {
			return;
		}
		$post->set('user_id', Auth::user()['id']);
		$result = EmpireModel::query('question_answer')->save([
			'question_id' => 'required|int',
			'parent_id' => 'required|int',
			'content' => 'required',
			'user_id' => '',
			'create_at' => ''
		], $post->get());
		if (empty($result)) {
			return;
		}
		$data = [];
		if ($question['status'] == 0 && $question['user_id'] != Auth::user()['id']) {
			$data['status'] = 1;
		}
		if ($question['user_id'] != Auth::user()['id'] && $post->get('parent_id') == 0) {
			$data['count'] = $question['count'] + 1;
			EmpireModel::message($question['user_id'], '您的问题有新的回答！', 
				Html::a('查看', ['question/view', 'id' => $question['id']]));
		}
		if (empty($data)) {
			return;
		}
		EmpireModel::query('question')->updateById($question['id'], $data);
	}
}