<?php
declare(strict_types=1);
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;
use Zodream\Html\Page;

class VisitController extends Controller {
    public function indexAction(string $start_at = '', string $end_at = '') {
        $items = StateRepository::allUrl(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function enterAction(string $start_at = '', string $end_at = '') {
        $items = StateRepository::enterUrl(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function domainAction(string $start_at = '', string $end_at = '') {
        $items = StateRepository::domain(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function districtAction(string $start_at = '', string $end_at = '') {
        $items = new Page(0);
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function clientAction(string $start_at = '', string $end_at = '') {
        $items = new Page(0);
        return $this->show(compact('items', 'start_at', 'end_at'));
    }
}