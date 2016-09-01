<?php
namespace Service\Admin;
/**
 * 废料科普
 */

use Domain\Model\Company\CompanyModel;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request\Post;

class CompanyController extends Controller {
	function indexAction() {
		$page = CompanyModel::find()->select('id,name,charge,update_at')->page();
		return $this->show(array(
			'title' => '公司管理',
			'page' => $page
		));
	}

	function addAction($id = null) {
		$model = empty($id) ? new CompanyModel() : CompanyModel::findOne($id);
		if ($model->load() && $model->save()) {
			return $this->redirect('company');
		}
		return $this->show(array(
			'title' => '新增公司',
			'data' => $model
		));
	}

	function deleteAction($id) {
		CompanyModel::findOne($id)->delete();
		return $this->redirect('company');
	}

	function viewAction($id) {
		$model = CompanyModel::findOne($id);
		return $this->show(array(
			'title' => '查看 '.$model->name,
			'data' => $model
		));
	}
}