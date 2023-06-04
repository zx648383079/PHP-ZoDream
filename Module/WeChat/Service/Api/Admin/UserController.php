<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Repositories\FollowRepository;

class UserController extends Controller {

    public function indexAction(int $wid = 0, string $keywords = '', int $group = 0, bool $blacklist = false) {
        try {
            return $this->renderPage(
                FollowRepository::manageList($wid, $keywords, $group, $blacklist)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}