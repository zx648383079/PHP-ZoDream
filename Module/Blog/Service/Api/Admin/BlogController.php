<?php
namespace Module\Blog\Service\Api\Admin;

use Module\Blog\Domain\Repositories\ManageRepository;

class BlogController extends Controller {

    public function indexAction(string $keywords = '', int $term = 0, int $user = 0, int $status = 0, int $type = 0, string $language = '') {
        return $this->renderPage(
            ManageRepository::getList($keywords, $term, $user, $status, $type, $language)
        );
    }


    public function changeAction(int $id, int $status = 0) {
        try {
            return $this->render(ManageRepository::manageChange($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }


    public function deleteAction(int $id) {
        try {
            ManageRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}