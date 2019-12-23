<?php
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;

class SourceController extends Controller {
    public function indexAction() {
        $time = strtotime(date('Y-m-d 00:00:00'));
        $items = StateRepository::allSource($time, $time + 86400);
        return $this->show(compact('items'));
    }

    public function engineAction() {
        $time = strtotime(date('Y-m-d 00:00:00'));
        $items = StateRepository::searchEngine($time, $time + 86400);
        return $this->show(compact('items'));
    }

    public function searchWordAction() {
        $time = strtotime(date('Y-m-d 00:00:00'));
        $items = StateRepository::searchKeywords($time, $time + 86400);
        return $this->show(compact('items'));
    }

    public function linkAction() {
        $time = strtotime(date('Y-m-d 00:00:00'));
        $items = StateRepository::host($time, $time + 86400);
        return $this->show(compact('items'));
    }
}