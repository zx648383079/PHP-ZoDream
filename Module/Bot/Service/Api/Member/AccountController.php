<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Member;

use Module\Bot\Domain\Repositories\AccountRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AccountController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            AccountRepository::selfList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                AccountRepository::getSelf($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,40',
                'token' => 'required|string:0,32',
                'platform_type' => 'int',
                'access_token' => 'string:0,255',
                'account' => 'string:0,30',
                'original' => 'string:0,40',
                'type' => 'int:0,9',
                'appid' => 'string:0,50',
                'secret' => 'string:0,50',
                'aes_key' => 'string:0,43',
                'avatar' => 'string:0,255',
                'qrcode' => 'string:0,255',
                'address' => 'string:0,255',
                'description' => 'string:0,255',
                'username' => 'string:0,40',
                'password' => 'string:0,32',
                'status' => 'int:0,9',
            ]);
            return $this->render(
                AccountRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            AccountRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}