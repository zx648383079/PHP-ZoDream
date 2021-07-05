<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\PresaleRepository;
use Module\Shop\Service\Api\Controller;

class PresaleController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            PresaleRepository::getList($keywords)
        );
    }

    public function detailAction(int $id, bool $full = false) {
        try {
            return $this->render(PresaleRepository::get($id, $full));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}