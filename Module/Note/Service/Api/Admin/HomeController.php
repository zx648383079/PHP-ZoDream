<?php
declare(strict_types=1);
namespace Module\Note\Service\Api\Admin;

use Module\Note\Domain\Repositories\NoteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class HomeController extends Controller {
    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            NoteRepository::getManageList($keywords, $user)
        );
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'content' => 'required|string:0,255',
                'is_notice' => 'int:0,9'
            ]);
            return $this->render(NoteRepository::save($data,
                empty($data['id']) ? auth()->id() : 0));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function changeAction(int $id, array $data) {
        try {
            return $this->render(NoteRepository::change($id, $data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            NoteRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}