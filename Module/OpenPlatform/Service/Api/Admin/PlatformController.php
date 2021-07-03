<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service\Api\Admin;

use Module\OpenPlatform\Domain\Repositories\PlatformRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PlatformController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            PlatformRepository::getList($keywords)
        );
    }

    public function editAction(int $id) {
        try {
            return $this->renderData(PlatformRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
                'type' => 'int:0,9',
                'domain' => 'required|string:0,50',
                'sign_type' => 'int:0,9',
                'sign_key' => 'string:0,32',
                'encrypt_type' => 'int:0,9',
                'public_key' => '',
                'rules' => '',
                'description' => '',
                'allow_self' => 'int',
                'status' => 'int:0,127',
            ]);
            return $this->renderData(PlatformRepository::save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            PlatformRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}