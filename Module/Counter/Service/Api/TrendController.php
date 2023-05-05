<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\StateRepository;
use Zodream\Html\Page;

class TrendController extends Controller {
    public function indexAction() {
        return $this->renderPage(StateRepository::currentStay());
    }

    public function analysisAction() {
        $items = new Page(0);
        return $this->renderPage($items);
    }
}