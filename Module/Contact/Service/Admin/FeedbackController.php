<?php
declare(strict_types=1);
namespace Module\Contact\Service\Admin;

use Module\Contact\Domain\Repositories\FeedbackRepository;

class FeedbackController extends Controller {

	function indexAction() {
	    $model_list = FeedbackRepository::getList();
        return $this->show(compact('model_list'));
	}

}