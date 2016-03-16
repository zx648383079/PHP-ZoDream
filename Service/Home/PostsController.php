<?php
namespace Service\Home;


use Domain\Form\CommentsForm;
use Domain\Model\PostsModel;
class PostsController extends Controller {
	protected $rules = array(
			'*' => '*'
	);
	
	function indexAction($id = 0) {
    	$form = new CommentsForm();
    	$form->set($id);
    	$form->get($id);
    	$model = new PostsModel();
    	$data = $model->findById($id);
    	$this->send('post', $data);
		$this->show('single', array(
				'title' => $data['title']
		));
	}
}