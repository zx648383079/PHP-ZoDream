<?php
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;

class VisitController extends Controller {
    public function indexAction($start_at = null, $end_at = null) {
        $items = StateRepository::allUrl(...$this->getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function enterAction($start_at = null, $end_at = null) {
        $items = StateRepository::enterUrl(...$this->getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function domainAction($start_at = null, $end_at = null) {
        $items = StateRepository::domain(...$this->getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function districtAction($start_at = null, $end_at = null) {
        return $this->show(compact('start_at', 'end_at'));
    }

    public function clientAction($start_at = null, $end_at = null) {
        return $this->show(compact('start_at', 'end_at'));
    }
}