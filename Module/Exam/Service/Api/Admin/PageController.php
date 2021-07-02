<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Admin;

use Module\Exam\Domain\Repositories\PageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PageController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            PageRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                PageRepository::get($id)
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
                'rule_type' => 'required|int:0,127',
                'rule_value' => 'required',
                'start_at' => '',
                'end_at' => '',
                'limit_time' => 'int:0,99999',
                'rule' => '',
            ]);
            return $this->render(
                PageRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            PageRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function evaluateAction(int $page_id, string $keywords = '') {
        return $this->renderPage(
            PageRepository::evaluateList($page_id, $keywords)
        );
    }

    public function evaluateDeleteAction(int $id) {
        try {
            PageRepository::evaluateRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}