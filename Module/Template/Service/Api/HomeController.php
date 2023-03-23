<?php
declare(strict_types=1);
namespace Module\Template\Service\Api;

use Module\Template\Domain\Repositories\ComponentRepository;

class HomeController extends Controller {


    public function indexAction() {
        return $this->renderData(true);
    }

    public function recommendAction(int $type = 0) {
        return $this->renderData(ComponentRepository::recommend($type));
    }
}