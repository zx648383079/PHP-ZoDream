<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\ModuleController as RestController;
use Module\Shop\Domain\Repositories\ShopRepository;

class Controller extends RestController {

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($passable, callable $next) {
            if (!$this->allowAccess()) {
                return $this->renderFailure('shop is closed', 403);
            }
            return $next($passable);
        });
    }

    protected function allowAccess(): bool {
        return ShopRepository::isOpen();
    }
}