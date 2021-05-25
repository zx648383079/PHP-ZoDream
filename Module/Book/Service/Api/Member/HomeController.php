<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Member;

use Module\Book\Domain\Repositories\AuthorRepository;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render(AuthorRepository::profileByAuth());
    }
}