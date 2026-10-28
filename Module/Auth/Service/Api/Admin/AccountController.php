<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Advanced\ApplyRepository;
use Module\Auth\Domain\Repositories\StatisticsRepository;

class AccountController extends Controller {

    public function applyAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(ApplyRepository::getList($keywords, 0, $user));
    }

    public function applySaveAction(int $id, $status = 0) {
        try {
            $model = ApplyRepository::change($id, $status);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function userAction(int $id, string $extra = '') {
        try {
            $model = StatisticsRepository::user($id, $extra);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}