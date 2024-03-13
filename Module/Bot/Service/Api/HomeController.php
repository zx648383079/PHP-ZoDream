<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api;

use Module\Bot\Domain\Repositories\EmulateRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            EmulateRepository::getList($keywords)
        );
    }
}