<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api\Admin;


use Module\Contact\Domain\Repositories\FeedbackRepository;

class FeedbackController extends Controller {

	function indexAction(string $keywords = '') {
        return $this->renderPage(
            FeedbackRepository::manageList($keywords)
        );
	}

	public function detailAction(int $id) {
	    return $this->render(FeedbackRepository::get($id));
    }

    public function changeAction(int $id, array $data) {
        try {
            return $this->render(FeedbackRepository::change($id, $data));
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