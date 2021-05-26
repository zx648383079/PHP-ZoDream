<?php
declare(strict_types=1);
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\AuthorRepository;

class AuthorController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(AuthorRepository::getList($keywords));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(AuthorRepository::profile($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}