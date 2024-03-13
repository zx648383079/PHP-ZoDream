<?php
declare(strict_types=1);
namespace Module\Bot\Service\Admin;

use Module\Bot\Domain\Repositories\LogRepository;

class LogController extends Controller {

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(bool $mark = false) {
        $log_list = LogRepository::getList($this->botId(), $mark);
        return $this->show(compact('log_list'));
    }

    public function markAction(int $id) {
        LogRepository::mark($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function deleteAction(int $id) {
        LogRepository::remove($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }
}