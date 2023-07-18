<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api\Admin;

use Domain\Repositories\ExplorerRepository;
use Zodream\Html\Page;

class ExplorerController extends Controller {

    public function indexAction(string $path = '', string $keywords = '', string $filter = '', int $page = 1) {
        $items = ExplorerRepository::search($path, $keywords, $filter, $page);
        return $items instanceof Page ? $this->renderPage($items) : $this->renderData($items);
    }

    public function driveAction() {
        return $this->renderData(ExplorerRepository::driveList());
    }
}