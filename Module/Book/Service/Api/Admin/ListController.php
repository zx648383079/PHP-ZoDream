<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Admin;

use Module\Book\Domain\Repositories\ListRepository;

class ListController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ListRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ListRepository::detail($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ListRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}