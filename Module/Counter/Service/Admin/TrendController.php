<?php
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;
use Zodream\Html\Page;

class TrendController extends Controller {
    public function indexAction() {
        $items = StateRepository::currentStay();
        return $this->show(compact('items'));
    }

    public function timeAction($start_at = null, $end_at = null) {
        $items = new Page(0);
        return $this->show(compact('items', 'start_at', 'end_at'));
    }
}