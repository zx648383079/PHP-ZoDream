<?php
namespace Module\Contact\Service\Admin;


use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Weights\FriendLink;

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
	    cache()->delete(FriendLink::KEY);
	    return $this->jsonSuccess([
	       'refresh' => true
        ]);
    }

    public function removeAction($id) {
        $model = FriendLinkModel::find($id);
        $model->status = 0;
        $model->save();
        cache()->delete(FriendLink::KEY);
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function deleteAction($id) {
        $model = FriendLinkModel::find($id);
        $model->delete();
        cache()->delete(FriendLink::KEY);
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

}