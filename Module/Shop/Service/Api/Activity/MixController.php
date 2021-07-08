<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\MixRepository;
use Module\Shop\Service\Api\Controller;

class MixController extends Controller {
    public function rules() {
        return [
            'buy' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            MixRepository::getList($keywords)
        );
    }

    public function buyAction(int $id) {
        try {
            return $this->render(MixRepository::buy($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}