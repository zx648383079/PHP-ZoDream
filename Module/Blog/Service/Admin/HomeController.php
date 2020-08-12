<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Repositories\BlogRepository;

class HomeController extends Controller {

    public function indexAction() {
        $subtotal = BlogRepository::subtotal();
        return $this->show(compact('subtotal'));
    }
}