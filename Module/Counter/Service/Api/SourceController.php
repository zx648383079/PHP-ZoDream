<?php
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\StateRepository;

class SourceController extends Controller {
    public function indexAction(string $type = '') {
        switch ($type) {
            case 'engine':
                return $this->engineAction();
            case 'keywords':
                return $this->searchWordAction();
            case 'link':
                return $this->linkAction();
            default:
                break;
        }
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