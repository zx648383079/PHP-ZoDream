<?php
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;
use Zodream\Html\Page;

class VisitController extends Controller {
    public function indexAction($start_at = null, $end_at = null) {
        $items = StateRepository::allUrl(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function enterAction($start_at = null, $end_at = null) {
        $items = StateRepository::enterUrl(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function domainAction($start_at = null, $end_at = null) {
        $items = StateRepository::domain(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function districtAction($start_at = null, $end_at = null) {
        $items = new Page(0);
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function clientAction($start_at = null, $end_at = null) {
        $items = new Page(0);
        return $this->show(compact('items', 'start_at', 'end_at'));
    }
}