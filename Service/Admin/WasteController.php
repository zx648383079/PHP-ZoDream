<?php
namespace Service\Admin;
/**
 * 废料科普
 */

use Domain\Model\Waste\WasteModel;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request\Post;

class WasteController extends Controller {
	function indexAction() {
		$page = WasteModel::find()->select('id,code,name,update_at')->page();
		return $this->show(array(
			'title' => '废料科普管理',
			'page' => $page
		));
	}

	function addAction($id = null) {
		$model = empty($id) ? new WasteModel() : WasteModel::findOne($id);
		if ($model->load() && $model->save()) {
			return $this->redirect('waste');
		}
		return $this->show(array(
			'title' => '新增标准',
			'data' => $model
		));
	}

	function deleteAction($id) {
		WasteModel::findOne($id)->delete();
		return $this->redirect('waste');
	}

	function viewAction($id) {
		$data = WasteModel::findOne($id);
		return $this->show(array(
			'title' => '查看 '.$data['code'],
			'data' => $data
		));
	}

	function companyAction($id) {
		$data = EmpireModel::query('waste')->findOne($id);
		$links = EmpireModel::query('waste_company')->findAll(['waste_id' => $id]);
		foreach ($links as &$item) {
			$item = $item['company_id'];
		}
		$company = EmpireModel::query('company')->findAll(null, 'id,name');
		$this->show(array(
			'title' => '绑定 '.$data['code'],
			'data' => $data,
			'company' => $company,
			'links' => $links
		));
	}

	/**
	 * @param Post $post
	 */
	function companyPost($post) {
		$waste = intval($post->get('waste'));
		if ($waste < 1) {
			$this->send('error', '添加失败！');
			return;
		}
		$company = $post->get('company');
		$data = array();
		foreach ($company as $item) {
			$data[] = [$waste, intval($item)];
		}
		$result = EmpireModel::query('waste_company')->delete(['waste_id' => $waste]);
		if (empty($result)) {
			$this->send('error', '添加失败！');
			return;
		}
		EmpireModel::query('waste_company')->addValues(['waste_id', 'company_id'], $data);
		Redirect::to(['waste']);
	}
	
}