<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Repositories\PagerRepository;

class PagerController extends Controller {

    public function indexAction(int $course = 0, int $type = 0, int $id = 0) {
        try {
            return $this->render(
                PagerRepository::createOrThrow($course, $type, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function checkAction(array $question, int $id = 0, int $page_id = 0) {
        return $this->render(
            PagerRepository::check($question, $id, $page_id)
        );
    }

    public function saveAction(array $question, int $id = 0, int $page_id = 0) {
        try {
            PagerRepository::save($question, $id, $page_id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}