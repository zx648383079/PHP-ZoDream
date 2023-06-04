<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;


use Exception;
use Module\Game\GameMaker\Domain\Repositories\CharacterRepository;

class CharacterController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(CharacterRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}