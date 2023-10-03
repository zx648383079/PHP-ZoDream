<?php
namespace Module\Shop\Service\Mobile;

use Module\ModuleController;
use Module\Shop\Domain\Repositories\ShopRepository;
use Zodream\Disk\File;


class Controller extends ModuleController {

    protected File|string $layout = '/Mobile/layouts/main';
    public function __construct() {
        parent::__construct();
        $this->middleware(function ($passable, callable $next) {
            if (!ShopRepository::isOpen()) {
                return $this->redirectWithMessage('/', '禁止访问');
            }
            return $next($passable);
        });
    }

    protected function getUrl(string $path, array $args = []) {
        return url('./mobile/'.$path, $args);
    }


    public function redirectWithAuth() {
        return $this->redirect(['./mobile/member/login', 'redirect_uri' => url()->full()]);
    }
}