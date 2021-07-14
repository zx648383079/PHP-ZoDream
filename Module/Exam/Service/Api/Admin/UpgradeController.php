<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Admin;

use Module\Exam\Domain\Repositories\UpgradeRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class UpgradeController extends Controller {
    public function indexAction(string $keywords = '', int $course = 0) {
        return $this->renderPage(
            UpgradeRepository::getList($keywords, $course)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                UpgradeRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
            ]);
            return $this->render(
                UpgradeRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            UpgradeRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}