<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Admin;

use Module\Legwork\Domain\Repositories\ProviderRepository;

class ProviderController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ProviderRepository::getList($keywords)
        );
    }

    public function changeAction(int $id, int $status) {
        try {
            return $this->render(ProviderRepository::change($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function changeCategoryAction(int $id, int|array $category, int $status) {
        try {
            ProviderRepository::changeCategory($id, $category, $status);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}