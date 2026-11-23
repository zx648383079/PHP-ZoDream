<?php
declare(strict_types=1);
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;
use Zodream\Html\Page;

class TrendController extends Controller {
    public function indexAction() {
        $items = StateRepository::currentStay();
        return $this->show(compact('items'));
    }

    public function timeAction(string $start_at = '', string $end_at = '') {
        $items = new Page(0);
        return $this->show(compact('items', 'start_at', 'end_at'));
    }
}