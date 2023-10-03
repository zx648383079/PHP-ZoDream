<?php
namespace Module\Shop\Service;

use Module\ModuleController;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Repositories\ArticleRepository;
use Module\Shop\Domain\Repositories\ShopRepository;
use Module\Shop\Module;
use Zodream\Disk\File;

class Controller extends ModuleController {
    protected File|string $layout = 'main';

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($passable, callable $next) {
            if (!$this->allowAccess()) {
                return $this->redirectWithMessage('/', '禁止访问');
            }
            return $next($passable);
        });
    }

    protected function allowAccess(): bool {
        return ShopRepository::isOpen();
    }

    public function sendWithShare() {
        $categories_tree = CategoryModel::cacheTree();
        $cart = Module::cart();
        $notice_list = ArticleRepository::getNotices();
        $this->send(compact('categories_tree', 'cart', 'notice_list'));
        return $this;
    }

    public function redirectWithAuth() {
        return $this->redirect(['./member/login', 'redirect_uri' => url()->full()]);
    }
}