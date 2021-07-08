<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\PresaleRepository;
use Module\Shop\Service\Api\Controller;

class PresaleController extends Controller {

    public function rules() {
        return [
            'buy' => '@',
            '*' => '*'
        ];
    }

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

    public function buyAction(int $id, int $amount = 1) {
        try {
            return $this->render(PresaleRepository::buy($id, $amount));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}