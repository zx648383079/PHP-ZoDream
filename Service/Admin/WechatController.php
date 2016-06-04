<?php
namespace Service\Admin;
/**
 * 微信
 */
use Domain\Model\WeChat\FanModel;
use Domain\Model\WeChat\GroupModel;
use Domain\Model\WeChat\MaterialModel;
use Domain\Model\WeChat\MenuModel;
use Domain\Model\WeChat\MessageModel;
use Domain\Model\WeChat\ReplyModel;
use Domain\Model\WeChat\WeChatModel;
use Infrastructure\HtmlExpand;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request\Post;
use Zodream\Infrastructure\Session;

class WechatController extends Controller {
	function indexAction() {
		$model = new WeChatModel();
		$this->show(array(
			'data' => $model->findAll()
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
		$post['wechat_id'] = Session::getValue('wechat.id');
		$model = new ReplyModel();
		$result = $model->fill($post);
		if (!empty($result)) {
			Redirect::to('wechat/reply');
		}
	}
	
	function sendAction() {
		$this->show(['title' => '群发消息']);
	}

	function messageAction() {
		$model = new MessageModel();
		$this->show(array(
			'page' => $model->getPage()
		));
	}

	function fanAction($group = null) {
		$model = new FanModel();
		$groups = new GroupModel();
		$where = null;
		if ($group == -1) {
			$where = ['group_id' => 0];
		}
		if ($group > 0) {
			$where = ['group_id' => $group];
		}
		$this->show(array(
			'page' => $model->getPage(['where' => $where]),
			'group' => $groups->findAll()
		));
	}

	function menuAction() {
		$model = new MenuModel();
		$this->show(array(
			'title' => '自定义菜单管理',
			'data' => HtmlExpand::getTree($model->findAll([
				'order' => [
					'parent asc',
					'position asc'
				]
			]), 'parent')
		));
	}
	
	function mediaAction() {
		$model = new MaterialModel();
		$this->show([
			'title' => '素材管理',
			'page' => $model->getPage()
		]);
	}
}