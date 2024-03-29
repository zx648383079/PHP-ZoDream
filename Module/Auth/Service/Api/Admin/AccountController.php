<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Model\ApplyLogModel;
use Module\Auth\Domain\Repositories\AccountRepository;
use Module\Auth\Domain\Repositories\StatisticsRepository;

class AccountController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(
            AccountRepository::logList($keywords, '', $user)
        );
    }

    public function rechargeAction(int $user_id, float $money, string $remark, int $type = 0) {
        try {
            AccountRepository::recharge($user_id, $money, $remark, $type);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function applyAction(int $user_id = 0) {
        $log_list = ApplyLogModel::with('user')
            ->when($user_id > 0, function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orderBy('id', 'desc')
            ->page();
        return $this->renderPage($log_list);
    }

    public function applySaveAction(int $id, $status = 0) {
        $model = ApplyLogModel::find($id);
        if ($model->status == $status) {
            return $this->render($model);
        }
        $model->status = $status;
        $model->save();
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