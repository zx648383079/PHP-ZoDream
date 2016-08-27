<?php
namespace Service\Admin;

/**
 * 设置
 */

use Domain\Model\OptionModel;
use Zodream\Infrastructure\Database\Command;
use Zodream\Infrastructure\Request\Post;

class OptionController extends Controller {
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
	    $model = new OptionModel();
        $model->delete(['name' => ':name'], [':name' => $name]);
        return $this->redirect('option');
    }
}