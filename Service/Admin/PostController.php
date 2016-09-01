<?php
namespace Service\Admin;

/**
 * 博客
 */

use Domain\Model\Blog\CommentModel;
use Domain\Model\Blog\PostModel;
use Domain\Model\Blog\TermModel;
use Infrastructure\HtmlExpand;
use Zodream\Domain\Html\Page;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Database\Command;
use Zodream\Infrastructure\ObjectExpand\PinYin;

class PostController extends Controller {

	function indexAction($search = null) {
		$where = array();
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "p.title like '%{$item}%'";
			}
		}
		$page = new Page(PostModel::find()->where($where));
		$page->setPage(PostModel::find()
			->alias('p')
			->load(array(
				'left' => array(
					'user u',
					'p.user_id = u.id',
					'term_post tp',
					'p.id = tp.post_id',
					'term t',
					'tp.term_id = t.id'
				),
				'where' => $where,
				'order' => 'create_at desc',
				'select' => array(
					'id' => 'p.id',
					'title' => 'p.title',
					'user' => 'u.name',
					'term' => 't.name',
					'comment_count' => 'p.comment_count',
					'create_at' => 'p.create_at',
				)
			)));
		return $this->show(array(
			'page' => $page
		));
	}

	function addAction($id = null) {
        if (empty($id)) {
            $model = new PostModel();
        } else {
            $model = PostModel::findOne($id);
        }
        $term = TermModel::findAll();
		return $this->show(array(
			'term' => $term,
			'model' => $model
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
		$post->set('excerpt', HtmlExpand::shortString($post->get('content')));
		$id = EmpireModel::query('post')->save(array(
			'id' => '',
			'title' => 'required|string:1-100',
			'content' => 'required',
			'status' => 'required|enum:publish,password,private,draft',
			'password' => '',
			'create_at' => '',
			'user_id' => '',
			'excerpt' => '',
			'update_at' => ''
		), $post->get());
		if (empty($id)) {
			return;
		}
		if (!empty($post->get('id'))) {
			$id = intval($post->get('id'));
		}
		Command::getInstance()->setTable('term_post')->insertOrUpdate(
			'post_id, term_id', ':post, :term', 'term_id = :term', array(
			':post' => $id,
			':term' => intval($post->get('term_id'))
		));
		Redirect::to('post');
	}

	function deleteAction($id) {
		EmpireModel::query('term_post')->delete(array(
			'post_id' => $id
		));
		$this->delete('post', $id);
	}

	function termAction() {
		$data = TermModel::findAll();
		return $this->show(array(
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
		$data = CommentModel::find()->Page();
		return $this->show(array(
			'page' => $data
		));
	}
	
	function deleteCommentAction($id) {
		CommentModel::findOne($id)->delete();
		return $this->redirect(['post/comment']);
	}
}