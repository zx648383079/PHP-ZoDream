<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\GroupBuyRepository;
use Module\Shop\Service\Api\Controller;

class GroupBuyController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            GroupBuyRepository::getList($keywords)
        );
    }
}