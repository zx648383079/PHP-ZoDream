<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\Repositories\FollowRepository;

class UserController extends Controller {

    public function indexAction(int $bot_id = 0, string $keywords = '', int $group = 0, bool $blacklist = false) {
        try {
            return $this->renderPage(
                FollowRepository::manageList($bot_id, $keywords, $group, $blacklist)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}