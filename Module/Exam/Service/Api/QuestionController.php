<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Repositories\PagerRepository;

class QuestionController extends Controller {

    public function indexAction(int $id = 0, int $course = 0) {
        try {
            return $this->render(
                PagerRepository::question($id, $course)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function checkAction(array $question) {
        return $this->renderData(
            PagerRepository::questionCheck($question)
        );
    }

    public function cardAction(int $course) {
        return $this->renderData(
            PagerRepository::questionCard($course)
        );
    }

}