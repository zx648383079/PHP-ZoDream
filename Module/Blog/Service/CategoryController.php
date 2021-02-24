<?php
declare(strict_types=1);
namespace Module\Blog\Service;


use Module\Blog\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    public function indexAction() {
        $cat_list = CategoryRepository::tree();
        return $this->show(compact('cat_list'));
    }
}