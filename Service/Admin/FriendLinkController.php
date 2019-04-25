<?php
namespace Service\Admin;


use Module\Template\Domain\Model\Base\FriendLinkModel;

class FriendLinkController extends Controller {

	function indexAction() {
	    $model_list = FriendLinkModel::orderBy([
	        'status' => 'asc',
	        'id' => 'desc'
        ])->page();
        return $this->show(compact('model_list'));
	}

}