<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\ItemRepository;

class ItemController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(ItemRepository::getList($keywords));
    }
}