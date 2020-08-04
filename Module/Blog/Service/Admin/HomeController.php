<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\BlogRepository;

class HomeController extends Controller {

    public function indexAction() {
        $subtotal = BlogRepository::subtotal();
        return $this->show(compact('subtotal'));
    }
}