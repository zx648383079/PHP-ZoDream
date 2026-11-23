<?php
declare(strict_types=1);
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;

class SourceController extends Controller {
    public function indexAction(string $start_at = '', string $end_at = '') {
        $items = StateRepository::allSource(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function engineAction(string $start_at = '', string $end_at = '') {
        $items = StateRepository::searchEngine(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function searchWordAction(string $start_at = '', string $end_at = '') {
        $items = StateRepository::searchKeywords(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }

    public function linkAction(string $start_at = '', string $end_at = '') {
        $items = StateRepository::host(...StateRepository::getTimeInput());
        return $this->show(compact('items', 'start_at', 'end_at'));
    }
}