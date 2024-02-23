<?php
declare(strict_types=1);
namespace Module\Note\Service;

use Module\ModuleController;
use Module\Note\Domain\Repositories\NoteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class NoteController extends ModuleController {

	protected array $rules = array(
		'*' => '@'
	);

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'content' => 'required|string:0,255',
                'status' => 'int:0,9'
            ]);
            NoteRepository::saveSelf($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function deleteAction(int $id) {
        NoteRepository::removeSelf($id);
        if (request()->isAjax()) {
            return $this->renderData(true);
        }
        return $this->redirect('./');
	}

}