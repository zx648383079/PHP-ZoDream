<?php
declare(strict_types=1);
namespace Module\Note\Service\Admin;

use Module\Note\Domain\Repositories\NoteRepository;

class NoteController extends Controller {

    public function indexAction(string $keywords = '') {
        $model_list = NoteRepository::getManageList($keywords);
        return $this->show(compact('model_list'));
    }

    public function deleteAction(int $id) {
        try {
            NoteRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('note')
        ]);
    }
}