<?php
namespace Module\WeChat\Service\Api;

use Module\WeChat\Domain\EmulateResponse;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Model\WeChatSimpleModel;
use Module\WeChat\Domain\Repositories\EmulateRepository;
use Module\WeChat\Module;

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
            return $this->render(EmulateRepository::reply($id, $content, $type));
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