<?php
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;

class SourceController extends Controller {
    public function indexAction($start_at = null, $end_at = null) {
        $items = StateRepository::allSource(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function engineAction($start_at = null, $end_at = null) {
        $items = StateRepository::searchEngine(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function searchWordAction($start_at = null, $end_at = null) {
        $items = StateRepository::searchKeywords(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function linkAction($start_at = null, $end_at = null) {
        $items = StateRepository::host(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }
}