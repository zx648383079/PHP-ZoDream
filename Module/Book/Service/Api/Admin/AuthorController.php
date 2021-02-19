<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Admin;

use Module\Book\Domain\Repositories\AuthorRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AuthorController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            AuthorRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                AuthorRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'avatar' => 'string:0,200',
                'description' => 'string:0,200',
            ]);
            return $this->render(
                AuthorRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            AuthorRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        return $this->renderData(AuthorRepository::search($keywords, $id));
    }
}