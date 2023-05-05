<?php
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\StateRepository;

class SourceController extends Controller {
    public function indexAction() {
        $items = StateRepository::allSource(...StateRepository::getTimeInput());
        return $this->renderData($items);
    }

    public function engineAction() {
        $items = StateRepository::searchEngine(...StateRepository::getTimeInput());
        return $this->renderData($items);
    }

    public function searchWordAction() {
        $items = StateRepository::searchKeywords(...StateRepository::getTimeInput());
        return $this->renderData($items);
    }

    public function linkAction() {
        $items = StateRepository::host(...StateRepository::getTimeInput());
        return $this->renderData($items);
    }
}