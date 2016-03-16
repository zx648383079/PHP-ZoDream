<?php
namespace Service\Admin;

use Domain\Model\OptionsModel;
use Zodream\Domain\Response\Redirect;
use Domain\Form\OptionsForm;
class SinglesController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new OptionsModel();
		$this->send('singles', $model->findSingle());
		$this->show();
	}
	
	function addAction() {
		$form = new OptionsForm();;
		$form->addIndex();
		$this->show('Singles/edit');
	}
	
	function editAction($id) {
		$form = new OptionsForm();
		$form->setPage($id);
		$this->show();
	}
	
	function deleteAction($id) {
		$model = new PostsModel();
		$data  = $model->deleteById($id);
		Redirect::to('singles');
	}
	
	function viewAction($id = 0) {
		$model = new OptionsModel();
		$this->send('single', $model->findById($id));
		$this->show();
	}
}