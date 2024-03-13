<?php
declare(strict_types=1);
namespace Module\Bot\Service;

use Module\Bot\Domain\Repositories\BotRepository;
use Zodream\Infrastructure\Contracts\Http\Output;

class MessageController extends Controller {
    public function indexAction(Output $output, int $id) {
        try {
            $adapter = BotRepository::entry($id);
            return $adapter->listen();
        } catch (\Exception $ex) {
            logger($ex->getMessage());
            return $output->statusCode(400);
        }
    }


    public function throwErrorMethod($action) {
        return $this->indexAction(response(), intval($action));
    }
}