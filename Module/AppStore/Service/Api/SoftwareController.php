<?php
declare(strict_types=1);
namespace Module\AppStore\Service\Api;

use Module\AppStore\Domain\Repositories\AppRepository;

class SoftwareController extends Controller {

    public function indexAction(string $keywords = '', int $category = 0) {
        return $this->renderPage(
            AppRepository::getList($keywords, $category)
        );
    }

    public function detailAction(int $id, int $version = 0) {
        try {
            return $this->render(
                AppRepository::getFull($id, $version)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function downloadAction(int $id) {

    }

    public function checkAction(array $items) {
        try {
            return $this->render(
                AppRepository::check($items)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function suggestAction(string $keywords) {
        return $this->renderData(AppRepository::suggestion($keywords));
    }
}