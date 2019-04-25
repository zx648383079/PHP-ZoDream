<?php
namespace Service\Admin;


use Module\Template\Domain\Model\Base\FeedbackModel;

class FeedbackController extends Controller {

	function indexAction() {
	    $model_list = FeedbackModel::orderBy([
	        'status' => 'asc',
	        'id' => 'desc'
        ])->page();
        return $this->show(compact('model_list'));
	}

}