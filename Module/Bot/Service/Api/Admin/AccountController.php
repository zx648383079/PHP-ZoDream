<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\Repositories\AccountRepository;

class AccountController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            AccountRepository::manageList($keywords)
        );
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