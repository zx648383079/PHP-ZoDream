<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Repositories\PagerRepository;

class PagerController extends Controller {

    public function indexAction(int $course, int $type = 0) {
        return $this->render(
            PagerRepository::create($course, $type)
        );
    }

    public function checkAction(array $question) {
        return $this->render(
            PagerRepository::check($question)
        );
    }

}