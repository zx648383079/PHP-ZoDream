<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\MaterialsModel;
class MaterialsForm extends Form {
	public function get($id) {
		$model = new MaterialsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,media_id,original_id,parent_id,type,is_multi,can_edited,title,description,author,content,cover_media_id,cover_url,show_cover_pic,created_from,source_url,content_url,created_at,updated_at,deleted_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'media_id' => 'required',
			'original_id' => 'required',
			'parent_id' => 'required',
			'type' => 'required',
			'is_multi' => 'required',
			'can_edited' => 'required',
			'title' => 'required',
			'description' => 'required',
			'author' => 'required',
			'content' => 'required',
			'cover_media_id' => 'required',
			'cover_url' => 'required',
			'show_cover_pic' => 'required',
			'created_from' => 'required',
			'source_url' => 'required',
			'content_url' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required',
			'deleted_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new MaterialsModel();
		$model->add($data);
	}
}