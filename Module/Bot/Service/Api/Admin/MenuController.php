<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\Repositories\MenuRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class MenuController extends Controller {

    public function indexAction(int $bot_id = 0) {
        try {
            return $this->renderPage(
                MenuRepository::manageList($bot_id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}