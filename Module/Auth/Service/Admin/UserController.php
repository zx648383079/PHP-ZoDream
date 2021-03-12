<?php
declare(strict_types=1);
namespace Module\Auth\Service\Admin;

use Module\Auth\Domain\Events\CancelAccount;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class UserController extends Controller {

    public function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    public function indexAction(string $keywords = '') {
        $user_list = UserRepository::getAll($keywords);
        return $this->show(compact('user_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = UserModel::findOrNew($id);
        $role_list = RoleModel::all();
        return $this->show('edit', compact('model', 'role_list'));
    }

    public function saveAction(Request $request) {
        try {
            UserRepository::save($request->get(), $request->get('roles', []));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('user')
        ]);
    }

    public function deleteAction(int $id) {
        try {
            UserRepository::remove($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('user')
        ]);
    }

    public function accountAction($id) {
        $user = UserModel::find($id);
        $log_list = AccountLogModel::where('user_id', $id)->orderBy('id', 'desc')
            ->page();
        return $this->show(compact('user', 'log_list'));
    }

    public function rechargeAction($id) {
        $user = UserModel::find($id);
        return $this->show(compact('user'));
    }

    public function rechargeSaveAction($user_id, $money, $remark, $type = 0) {
        $money = abs(intval($money));
        if ($money <= 0) {
            return $this->renderFailure('金额输入不正确');
        }
        if ($type > 0) {
            $money *= -1;
        }
        if (AccountLogModel::change($user_id,
            AccountLogModel::TYPE_ADMIN, auth()->id(), $money, $remark, 1)) {
            return $this->renderData([
                'url' => url('./@admin/user/account', ['id' => $user_id])
            ], '充值成功');
        }
        return $this->renderFailure('操作失败，金额不足');
    }
}