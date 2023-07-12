<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api;

use Module\Game\GameMaker\Domain\Repositories\GameRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PlayController extends Controller {

    public function indexAction(Input $input, int $project, int $character = 0) {
        try {
            $store = GameRepository::enter($project, $character);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        $batch = $input->get('batch');
        if (empty($batch) || !is_array($batch)) {
            $res = GameRepository::execute($store, $input->string('command'), $input->get('data'));
            return isset($res['message']) ? $this->renderFailure($res) : $this->render($res);
        }
        $items = [];
        foreach ($batch as $command => $data) {
            $items[$command] = GameRepository::execute($store, $command, $data);
        }
        return $this->render([
            'batch' => $items
        ]);
    }
}