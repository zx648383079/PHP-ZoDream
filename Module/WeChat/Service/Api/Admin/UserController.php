<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Repositories\FollowRepository;

class UserController extends Controller {

    public function indexAction(string $keywords = '', bool $blacklist = false) {
        try {
            return $this->renderPage(
                FollowRepository::getList($this->weChatId(), $keywords, $blacklist)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function refreshAction() {
        try {
            FollowRepository::async($this->weChatId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}