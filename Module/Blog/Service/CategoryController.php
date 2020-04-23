<?php
namespace Module\Blog\Service;



use Module\Blog\Domain\Repositories\TermRepository;

class CategoryController extends Controller {

    public function indexAction() {
        $cat_list = TermRepository::tree();
        return $this->show(compact('cat_list'));
    }
}