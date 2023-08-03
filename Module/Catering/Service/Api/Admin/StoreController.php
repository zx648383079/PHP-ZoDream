<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Admin;

use Module\Catering\Domain\Repositories\StoreRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class StoreController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            StoreRepository::manageGetList($keywords, $user)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                StoreRepository::manageGet($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'user_id' => 'required|int',
                'name' => 'required|string:0,20',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'logo' => 'string:0,255',
                'status' => 'int:0,127',
            ]);
            return $this->render(
                StoreRepository::manageSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            StoreRepository::manageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}