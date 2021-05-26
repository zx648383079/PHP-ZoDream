<?php
namespace Module\Game\CheckIn\Service\Admin;

use Module\Game\CheckIn\Domain\Repositories\CheckinRepository;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show(
            CheckinRepository::statistics()
        );
    }
}