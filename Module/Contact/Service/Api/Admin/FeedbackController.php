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

	public function detailAction($id) {
	    return $this->render(FeedbackModel::find($id));
    }

    public function changeAction($id, $status) {
        $model = FeedbackModel::find($id);
        $model->status = intval($status);
        $model->save();
        return $this->render($model);
    }

    public function deleteAction($id) {
	    FeedbackModel::where('id', $id)->delete();
	    return $this->renderData(true);
    }

}