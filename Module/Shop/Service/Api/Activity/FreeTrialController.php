<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\TrialRepository;
use Module\Shop\Service\Api\Controller;
use Zodream\Infrastructure\Contracts\Http\Input;

class FreeTrialController extends Controller {
    public function rules() {
        return [
            'apply' => '@',
            'report' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            TrialRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(TrialRepository::get($id, true));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logAction(int $activity) {
        return $this->renderPage(
            TrialRepository::logList($activity)
        );
    }

    public function applyAction(int $id) {
        try {
            TrialRepository::apply($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function reportAction(Input $input, int $id) {
        try {
            $data = $input->validate([

            ]);
            TrialRepository::saveReport($id, $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}