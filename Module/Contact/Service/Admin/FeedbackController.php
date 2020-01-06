<?php
namespace Module\Contact\Service\Admin;

use Module\Contact\Domain\Model\FeedbackModel;

class FeedbackController extends Controller {

	function indexAction() {
	    $model_list = FeedbackModel::orderBy([
	        'status' => 'asc',
	        'id' => 'desc'
        ])->page();
        return $this->show(compact('model_list'));
	}

}