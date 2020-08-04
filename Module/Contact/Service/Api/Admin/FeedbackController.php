<?php
namespace Module\Contact\Service\Api\Admin;

use Module\Contact\Domain\Model\FeedbackModel;

class FeedbackController extends Controller {

	function indexAction() {
	    $model_list = FeedbackModel::orderBy([
	        'status' => 'asc',
	        'id' => 'desc'
        ])->page();
        return $this->renderPage($model_list);
	}

}