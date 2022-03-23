<?php
declare(strict_types=1);
namespace Module\Note\Service\Api;

use Module\ModuleController as Controller;
use Module\Note\Domain\Repositories\NoteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class HomeController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $id = 0) {
        return $this->renderPage(
            NoteRepository::getList($keywords, $user, $id)
        );
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'content' => 'required|string:0,255',
            ]);
            return $this->render(NoteRepository::saveSelf($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            NoteRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}