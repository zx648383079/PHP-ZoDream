<?php
namespace Module\Contact\Service\Api\Admin;


use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Weights\FriendLink;

class FriendLinkController extends Controller {

	public function indexAction() {
	    $model_list = FriendLinkModel::orderBy([
	        'status' => 'asc',
	        'id' => 'desc'
        ])->page();
        return $this->renderPage($model_list);
	}

	public function verifyAction($id) {
	    $model = FriendLinkModel::find($id);
	    $model->status = 1;
	    $model->save();
	    cache()->delete(FriendLink::KEY);
	    return $this->render($model);
    }

    public function removeAction($id) {
        $model = FriendLinkModel::find($id);
        $model->status = 0;
        $model->save();
        cache()->delete(FriendLink::KEY);
        return $this->renderData(true);
    }

    public function deleteAction($id) {
        $model = FriendLinkModel::find($id);
        $model->delete();
        cache()->delete(FriendLink::KEY);
        return $this->renderData(true);
    }

}