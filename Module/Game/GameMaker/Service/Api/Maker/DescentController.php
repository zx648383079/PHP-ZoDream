<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Exception;
use Module\Game\GameMaker\Domain\Repositories\DescentRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class DescentController extends Controller {

    public function indexAction(int $project, string $keywords = '') {
        try {
            return $this->renderPage(DescentRepository::makerList($project, $keywords));
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'project_id' => 'required|int',
                'name' => 'required|string:0,255',
                'description' => 'string:0,255',
                'hp' => 'int',
                'mp' => 'int',
                'att' => 'int',
                'def' => 'int',
                'crt' => 'int',
                'lck' => 'int',
                'dex' => 'int',
                'chr' => 'int',
                'int' => 'int',
                'specialty' => 'string:0,255',
            ]);
            return $this->render(
                DescentRepository::makerSave($data)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $project, int $id) {
        try {
            DescentRepository::makerRemove($project, $id);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}