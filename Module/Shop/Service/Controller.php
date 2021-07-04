<?php
namespace Module\Shop\Service;

use Module\ModuleController;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Repositories\ArticleRepository;
use Module\Shop\Module;

class Controller extends ModuleController {
    public $layout = 'main';
    protected bool $disallow = true;

    public function __construct() {
        parent::__construct();
        $this->middleware(function ($passable, callable $next) {
            if (!app()->isDebug() && $this->disallow) {
                return $this->redirectWithMessage('/', '禁止访问');
            }
            return $next($passable);
        });
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