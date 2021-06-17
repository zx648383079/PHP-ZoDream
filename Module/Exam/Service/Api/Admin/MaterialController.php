<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Admin;

use Module\Exam\Domain\Repositories\MaterialRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MaterialController extends Controller {
    public function indexAction(string $keywords = '', int $course = 0) {
        return $this->renderPage(
            MaterialRepository::getList($keywords, $course)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                MaterialRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'course_id' => 'required',
                'title' => 'required|string:0,255',
                'description' => 'required|string:0,255',
                'type' => '',
                'content' => '',
            ]);
            return $this->render(
                MaterialRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            MaterialRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}