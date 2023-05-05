<?php
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\StateRepository;
use Zodream\Html\Page;

class VisitController extends Controller {
    public function indexAction() {
        $items = StateRepository::allUrl(...StateRepository::getTimeInput());
        return $this->renderPage($items);
    }

    public function enterAction() {
        $items = StateRepository::enterUrl(...StateRepository::getTimeInput());
        return $this->renderPage($items);
    }

    public function domainAction() {
        $items = StateRepository::domain(...StateRepository::getTimeInput());
        return $this->renderData($items);
    }

    public function jumpAction() {
        $items = StateRepository::jump(...StateRepository::getTimeInput());
        return $this->renderPage($items);
    }

    public function districtAction() {
        $items = new Page(0);
        return $this->renderPage($items);
    }

    public function clientAction() {
        $items = new Page(0);
        return $this->renderPage($items);
    }
}