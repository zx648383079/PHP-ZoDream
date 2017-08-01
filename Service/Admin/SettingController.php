<?php
namespace Service\Admin;

/**
 * 设置
 */

use Domain\Model\OptionModel;

class SettingController extends Controller {
	function indexAction() {
	    $model = new OptionModel();
	    if ($model->load() && $model->save()) {

        }
		$data = OptionModel::findAll();
		return $this->show(array(
			'data' => $data
		));
	}

	function deleteAction($name) {
	    OptionModel::where(['name' => ':name'], [':name' => $name])
        ->delete();
        return $this->redirect('option');
    }
}