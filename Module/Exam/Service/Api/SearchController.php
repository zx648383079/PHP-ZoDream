<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Repositories\PageRepository;
use Module\Exam\Domain\Repositories\QuestionRepository;

class SearchController extends Controller {

    public function indexAction(string $keywords = '', int $type = 0, int $course = 0) {
        if ($type < 1) {
            return $this->renderPage(PageRepository::getList($keywords));
        }
        return $this->renderPage(QuestionRepository::searchList($keywords, $course));
    }

    public function suggestAction(string $keywords = '', int $type = 0, int $course = 0) {
        if ($type > 0) {
            return $this->renderData(
                QuestionRepository::suggestion($keywords, $course)
            );
        }
        return $this->renderData(
            PageRepository::suggestion($keywords)
        );
    }
}