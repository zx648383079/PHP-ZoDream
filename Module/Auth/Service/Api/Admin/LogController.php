<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\AdminLogModel;
use Zodream\Route\Controller\RestController;

class LogController extends RestController {

    use AdminRole;

    public function indexAction(int $user_id = 0) {
        $log_list = AdminLogModel::with('user')
            ->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orderBy('id', 'desc')
            ->page();
        return $this->renderPage($log_list);
    }

    public function actionAction(int $user_id = 0) {
        $log_list = ActionLogModel::with('user')
            ->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orderBy('id', 'desc')
            ->page();
        return $this->renderPage($log_list);
    }


}