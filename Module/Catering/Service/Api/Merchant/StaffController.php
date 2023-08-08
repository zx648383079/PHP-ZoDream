<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Module\Catering\Domain\Repositories\StaffRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class StaffController extends Controller {

	public function indexAction(string $keywords = '', int $role = 0) {
        return $this->renderPage(StaffRepository::merchantList($keywords, $role));
	}

    public function roleAction() {
        return $this->renderData(StaffRepository::merchantRoleList());
    }

    public function roleSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
                'description' => 'string:0,255',
                'action' => 'string:0,255',
            ]);
            return $this->render(
                StaffRepository::merchantRoleSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
    public function roleDeleteAction(int $id) {
        try {
            StaffRepository::merchantRoleRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}