<?php
namespace Module\Contact\Service\Admin;


use Module\Contact\Domain\Model\FriendLinkModel;

class FriendLinkController extends Controller {

	public function indexAction() {
	    $model_list = FriendLinkModel::orderBy([
	        'status' => 'asc',
	        'id' => 'desc'
        ])->page();
        return $this->show(compact('model_list'));
	}

	public function verifyAction($id) {
	    $model = FriendLinkModel::find($id);
	    $model->status = 1;
	    $model->save();
	    return $this->jsonSuccess([
	       'refresh' => true
        ]);
    }

}