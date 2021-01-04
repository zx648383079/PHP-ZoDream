<?php
namespace Module\Auth\Service\Admin;


use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AccountRepository;
use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class AccountController extends Controller {

    public function indexAction() {
        $model = auth()->user();
        return $this->show(compact('model'));
    }

    public function connectAction() {
        $model_list = AccountRepository::getConnect();
        return $this->show(compact('model_list'));
    }

    public function deleteConnectAction($id) {
        if (empty($id) || $id < 1) {
            return $this->renderFailure('请选择解绑的平台');
        }
        OAuthModel::where('user_id', auth()->id())
            ->where('id', $id)->delete();
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function loginLogAction($keywords = null) {
        $model_list = LoginLogModel::where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                LoginLogModel::searchWhere($query, 'ip');
            })
            ->orderBy('id desc')->page();
        return $this->show(compact('model_list'));
    }

    public function logAction($keywords = null) {
        $model_list = ActionLogModel::where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                LoginLogModel::searchWhere($query, 'ip');
            })
            ->orderBy('id desc')->page();
        return $this->show(compact('model_list'));
    }

    public function passwordAction() {
        return $this->show();
    }

    public function updateAction(Request $request) {
        try {
            $user = AuthRepository::updateProfile($request);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('user')
        ]);

    }

    public function updatePasswordAction() {
        $old_password = request()->get('old_password');
        $password = request()->get('password');
        $confirm_password = request()->get('confirm_password');
        if (empty($password)) {
            return $this->renderFailure('请输入密码');
        }
        if ($password != $confirm_password) {
            return $this->renderFailure('两次密码不一致！');
        }
        /** @var UserModel $model */
        $model = auth()->user();
        if (!$model->validatePassword($old_password)) {
            return $this->renderFailure('密码不正确！');
        }
        $model->setPassword($password);
        if ($model->save()) {
            auth()->user()->logout();
            return $this->renderData([
                'url' => url('./')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }


}