<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Member;


class EvaluateController extends Controller {
    public function indexAction() {
        return $this->renderData(true);
    }
}