<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;


use Exception;
use Module\Game\GameMaker\Domain\Repositories\CharacterRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CharacterController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(CharacterRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function identityAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(CharacterRepository::makerIdentityList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function identitySaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'name' => 'required|string:0,255',
                'image' => 'string:0,255',
                'description' => 'string:0,255',
                'hp' => 'int',
                'mp' => 'int',
                'att' => 'int',
                'def' => 'int',
            ]);
            return $this->render(
                CharacterRepository::makerIdentitySave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function identiyDeleteAction(int $project, int $id) {
        try {
            CharacterRepository::makerIdentityRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}