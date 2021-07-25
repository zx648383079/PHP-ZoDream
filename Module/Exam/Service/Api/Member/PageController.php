<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Member;

use Module\Exam\Domain\Repositories\PageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PageController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            PageRepository::selfList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                PageRepository::getSelf($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,200',
                'rule_type' => 'int:0,127',
                'rule_value' => '',
                'start_at' => '',
                'end_at' => '',
                'limit_time' => 'int',
                'rule' => '',
                'course_id' => 'int',
                'course_grade' => 'int',
                'question_items' => '',
            ]);
            return $this->render(
                PageRepository::selfSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            PageRepository::selfRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function evaluateAction(int $page_id, string $keywords = '') {
        if (!PageRepository::can($page_id)) {
            return $this->renderFailure('无法查看');
        }
        return $this->renderPage(
            PageRepository::evaluateList($page_id, $keywords)
        );
    }

    public function evaluateDeleteAction(int $id) {
        try {
            PageRepository::selfEvaluateRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}