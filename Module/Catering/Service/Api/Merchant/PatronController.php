<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Module\Catering\Domain\Repositories\PatronRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PatronController extends Controller {

	public function indexAction(string $keywords = '', int $group = 0) {
        return $this->renderPage(PatronRepository::merchantList($keywords, $group));
	}

    public function groupAction() {
        return $this->renderData(PatronRepository::merchantGroupList());
    }

    public function groupSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
                'discount' => 'int',
            ]);
            return $this->render(
                PatronRepository::merchantGroupSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
    public function groupDeleteAction(int $id) {
        try {
            PatronRepository::merchantGroupRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}