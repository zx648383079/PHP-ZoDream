<?php
namespace Service\Home;


use Domain\Model\Blog\CategoryModel;
use Domain\Model\Blog\PostModel;
use Zodream\Domain\Html\Page;

class BlogController extends Controller {

	protected $canCache = false;

	function indexAction($search = null, $category = null, $user = null) {
		$categories = CategoryModel::findAll();
		$where = array();
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "p.title like '%{$item}%'";
			}
		}
		if (!empty($category)) {
			$where[] = 'tp.category_id = '.intval($category);
		}
		if (!empty($user)) {
			$where[] = 'p.user_id = '.intval($user);
		}
		$page = new Page(PostModel::find()->where($where));
		$page->setPage(PostModel::find()->alias('p')->load(array(
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
		))->select([
			'id' => 'p.id',
			'title' => 'p.title',
			'user' => 'u.name',
			'user_id' => 'p.user_id',
			'term' => 't.name',
			'comment_count' => 'p.comment_count',
			'create_at' => 'p.create_at',
			'description' => 'p.description',
			'recommend' => 'p.recommend',
			'comment_count' => 'p.comment_count'
		])->limit($page->getLimit())->all());
		return $this->show(array(
			'title' => '博客',
			'page' => $page,
			'categories' => $categories,
			'category' => $category
		));
	}

	function viewAction($id = null) {
		$id = intval($id);
		if ($id < 1) {
			return $this->redirect('blog');
		}
		$data = PostModel::find()->alias('p')->load(array(
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
		))->select(array(
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
		))->one();
		return $this->show(array(
			'title' => $data['title'],
			'data' => $data,
			'links' => (new PostModel())->getNextAndBefore($id)
		));
	}

	public function recommendAction($id) {
		$id = intval($id);
		if (LogModel::hasLog('recommendBlog', $id)) {
			return $this->ajax(array(
				'status' => 'failure',
				'error' => '您已经推荐过了！'
			));
		}
		$result = (new PostModel())
			->updateOne('recommend', 'id = '. intval($id));
		if (empty($result)) {
			return $this->ajax(array(
				'status' => 'failure',
				'error' => '推荐失败，请重试！'
			));
		}
		LogModel::addLog($id, 'recommendBlog');
		return $this->ajax(array(
			'status' => 'success'
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