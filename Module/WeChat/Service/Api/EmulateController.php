<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api;

use Module\WeChat\Domain\Repositories\EmulateRepository;

class EmulateController extends Controller {

    public function indexAction(int $id = 1) {
        try {
            return $this->render(EmulateRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function replyAction(int $id, string $content, string $type = '') {
        try {
            return $this->renderData(EmulateRepository::reply($id, $content, $type));
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