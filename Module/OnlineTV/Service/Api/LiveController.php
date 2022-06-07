<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api;

use Module\OnlineTV\Domain\Repositories\LiveRepository;

class LiveController extends Controller {

    public function indexAction() {
        return $this->renderData(
            LiveRepository::getActiveList()
        );
    }

}