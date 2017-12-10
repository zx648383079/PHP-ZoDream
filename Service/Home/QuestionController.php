<?php
namespace Service\Home;

/**
 * 问答版块
 */

use Domain\Model\Question\QuestionAnswerModel;
use Domain\Model\Question\QuestionModel;
use Zodream\Domain\Access\Auth;
use Zodream\Html\Page;
use Zodream\Infrastructure\Support\Html;
use Zodream\Infrastructure\Http\Request;

class QuestionController extends Controller {
	
	protected function rules() {
		return array(
			'add' => '@'
		);
	}

	function indexAction() {
		$page = new Page(QuestionModel::find());
		$page->setPage(QuestionModel::find()->load(array(
			'left' => [
				'user u',
				'u.id = q.user_id'
			],
			'order' => 'status asc,create_at desc'
		))->select([
			'id' => 'q.id',
			'user' => 'u.name',
			'user_id' => 'q.user_id',
			'title' => 'q.title',
			'count' => 'q.count',
			'create_at' => 'q.create_at',
			'status' => 'q.status'
		]));
		return $this->show(array(
			'title' => '问答',
			'page' => $page
		));
	}

	function addAction() {
		$this->show([
			'title' => '我要提问'
		]);
	}


	function viewAction($id) {
		$data = QuestionModel::find()->alias('q')
			->left('user u', 'q.user_id = u.id')->where(['q.id' => $id])->select([
				'id' => 'q.id',
				'user' => 'u.name',
				'user_id' => 'q.user_id',
				'title' => 'q.title',
				'count' => 'q.count',
				'create_at' => 'q.create_at',
				'status' => 'q.status'
			])->one();
		$page = new Page(QuestionAnswerModel::find()->where(['question_id' => $id]));
		$page->setPage(QuestionAnswerModel::find()->alias('qa')
			->load(array(
			'left' => [
				'user u',
				'qa.user_id = u.id'
			],
			'where' => array(
				'qa.question_id' => $id
			),
			'order' => 'qa.status desc,qa.create_at asc'
		))->select(array(
				'user_id' => 'qa.user_id',
				'user' => 'u.name',
				'content' => 'qa.content',
				'create_at' => 'qa.create_at',
				'status' => 'qa.status',
				'parent_id' => 'qa.parent_id'
			)));
		return $this->show(array(
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