<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api\Admin;


use Module\Contact\Domain\Repositories\FeedbackRepository;

class FeedbackController extends Controller {

	function indexAction(string $keywords = '') {
        return $this->renderPage(
            FeedbackRepository::getList($keywords)
        );
	}

	public function detailAction(int $id) {
	    return $this->render(FeedbackRepository::get($id));
    }

    public function changeAction(int $id, int $status) {
        try {
            return $this->render(FeedbackRepository::change($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(array|int $id) {
        try {
            FeedbackRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}