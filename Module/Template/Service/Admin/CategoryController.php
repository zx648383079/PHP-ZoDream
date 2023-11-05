<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Template\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    use AdminRole;
    public function indexAction() {
        $items = CategoryRepository::all(true);
        return $this->show(compact('items'));
    }

}