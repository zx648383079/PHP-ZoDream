<?php
namespace Service\Admin;
/**
 * 微信
 */
use Domain\Model\WeChat\MessageModel;
use Domain\Model\WeChat\ReplyModel;
use Domain\Model\WeChat\WeChatModel;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request\Post;
use Zodream\Infrastructure\Session;

class WechatController extends Controller {
	function indexAction() {
		$model = new WeChatModel();
		$this->show(array(
			'data' => $model->find()
		));
	}

	function addAction($id = null) {
		if (!empty($id)) {
			$model = new WeChatModel();
			$this->send(array(
				'id' => $id,
				'data' => $model->findById($id)
			));
		}
		$this->show();
	}

	/**
	 * @param Post $post
	 */
	function addPost($post) {
		$model = new WeChatModel();
		$result = $model->fill($post->get());
		if (!empty($result)) {
			Redirect::to('wechat');
		}
	}

	function replyAction() {
		$model = new ReplyModel();
		$this->show(array(
			'page' => $model->getPage(null, array(
				'id',
				'type',
				'name',
				'update_at'
			))
		));
	}

	function deleteAction($id) {
		$this->delete('wechat', $id);
	}

	function changeAction($id) {
		$model = new WeChatModel();
		Session::setValue('wechat', $model->findById($id));
		Redirect::to('wechat', '2', '切换成功！');
	}

	function addReplyAction($id = null) {
		if (!empty($id)) {
			$model = new ReplyModel();
			$this->send(array(
				'id' => $id,
				'data' => $model->findById($id)
			));
		}
		$this->show();
	}

	/**
	 * @param Post $post
	 */
	function addReplyPost($post) {
		$model = new ReplyModel();
		$result = $model->fill($post->get());
		if (!empty($result)) {
			Redirect::to('wechat/reply');
		}
	}

	function messageAction() {
		$model = new MessageModel();
		$this->show(array(
			'page' => $model->getPage()
		));
	}
}