<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api;

use Module\WeChat\Domain\Repositories\EmulateRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            EmulateRepository::getList($keywords)
        );
    }
}