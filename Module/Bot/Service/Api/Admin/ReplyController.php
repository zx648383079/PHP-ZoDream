<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\EditorInput;
use Module\Bot\Domain\Repositories\FollowRepository;
use Module\Bot\Domain\Repositories\ReplyRepository;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ReplyController extends Controller {

    public function indexAction(int $bot_id = 0, string $event = '') {
        try {
            return $this->renderPage(
                ReplyRepository::manageList($bot_id, $event)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}