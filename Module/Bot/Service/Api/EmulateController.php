<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api;

use Module\Bot\Domain\Repositories\EmulateRepository;

class EmulateController extends Controller {

    public function indexAction(int $id = 1) {
        try {
            return $this->render(EmulateRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function replyAction(int $id) {
        try {
            return $this->renderData(EmulateRepository::reply($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function mediaAction(int $id) {
        try {
            return $this->render(EmulateRepository::media($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}