<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\AccountsModel;
class AccountsForm extends Form {
	public function get($id) {
		$model = new AccountsModel();
		$this->send('account', $model->findById($id));
	}
	
	public function set($id = null) {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,original_id,app_id,app_secret,wechat_account,account_type');
		if (!$this->validate($data, array(
			'name' => 'required',
			'original_id' => 'required',
			'wechat_account' => 'required',
			'account_type' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new AccountsModel();
		if (null != $data) {
			$model->updateById($id, $data);
			return;
		}
		$data['tag'] = $this->buildTag();
		$data['aes_key'] = $this->buildAesKey();
		$data['token'] = $this->buildToken();
		$data['created_at'] = $data['updated_at'] = time();
		$model = new AccountsModel();
		$model->add($data);
		Redirect::to('admin');
	}

	/**
	 * 创建识别tag.
	 *
	 * @return string tag
	 */
	public function buildTag() {
		return StringExpand::random(15);
	}

	/**
	 * 创建token.
	 *
	 * @return string token
	 */
	public function buildToken() {
		return StringExpand::random(10);
	}

	/**
	 * 创建aesKey.
	 *
	 * @return string aesKey
	 */
	public function buildAesKey() {
		return StringExpand::random(43);
	}
}