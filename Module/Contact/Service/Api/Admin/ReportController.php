<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api\Admin;


use Module\Contact\Domain\Repositories\ReportRepository;

class ReportController extends Controller {

	function indexAction(string $keywords = '', int $item_type = 0, int $item_id = 0, int $type = 0) {
        return $this->renderPage(
            ReportRepository::getList($keywords, $item_type, $item_id, $type)
        );
	}

	public function detailAction(int $id) {
	    return $this->render(ReportRepository::get($id));
    }

    public function changeAction(int $id, int $status) {
        try {
            return $this->render(ReportRepository::change($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int|array $id) {
        try {
            ReportRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}