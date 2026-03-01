<?php
declare(strict_types=1);
namespace Module\Team\Service\Api;

use Module\Team\Domain\Repositories\TeamRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class HomeController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction() {
        return $this->renderData(TeamRepository::all());
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                TeamRepository::detail($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function searchAction(string $keywords = '') {
        return $this->renderPage(
            TeamRepository::search($keywords)
        );
    }

    public function agreeAction(int $user, int $team) {
        try {
            TeamRepository::agree($user, $team);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function applyAction(int $team, string $remark = '') {
        try {
            TeamRepository::apply($team, $remark);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function applyLogAction(int $team) {
        return $this->renderPage(
            TeamRepository::applyLog($team)
        );
    }

    public function createAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,100',
                'logo' => 'required|string:0,100',
                'description' => 'string:0,100',
            ]);
            return $this->render(TeamRepository::create($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function disbandAction(int $id) {
        try {
            TeamRepository::disband($id);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    
}