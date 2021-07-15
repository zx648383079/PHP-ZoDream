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
                'course_id' => 'required|int',
                'course_grade' => 'int',
                'icon' => 'string:0,100',
                'description' => 'string:0,255',
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

    public function logAction(string $keywords = '', int $course = 0, int $upgrade = 0, int $user = 0) {
        return $this->renderPage(
            UpgradeRepository::logList($keywords, $course, $upgrade, $user)
        );
    }

    public function logDeleteAction(int $id) {
        try {
            UpgradeRepository::logRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}