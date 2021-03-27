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
                'rule_value' => 'required|string:0,255',
                'start_at' => 'int',
                'end_at' => 'int',
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
}