<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Domain\Repositories\FileRepository;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\OptionRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class UserController extends Controller {

    public function rules() {
        return [
            'check' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(string $extra = '') {
        return $this->render(UserRepository::getCurrentProfile($extra));
    }

    /**
     * 上传用户头像
     * @throws \Exception
     */
    public function avatarAction() {
        try {
            $data = FileRepository::uploadImage();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $user = auth()->user();
        $user->avatar = $data['url'];
        $user->save();
        return $this->render($user);
    }

    /**
     * 更新用户信息
     * @param Request $request
     * @throws \Exception
     */
    public function updateAction(Request $request) {
        try {
            $user = AuthRepository::updateProfile($request->all());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($user);
    }

    public function updateAccountAction(Request $request) {
        try {
            $user = AuthRepository::updateAccount($request->validate([
                'verify_type' => 'required',
                'verify' => 'required',
                'name' => 'required',
                'value' => 'required',
                'code' => 'required'
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($user);
    }


    /**
     * 验证邮箱是否注册
     * @param $email
     * @throws \Exception
     */
    public function checkAction(string $email) {
        $user = UserModel::findByEmail($email);
        if (empty($user)) {
            return $this->renderFailure('email error');
        }
        return $this->render([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar
        ]);
    }

    /**
     * 获取用户的角色和权限
     * @throws \Exception
     */
    public function roleAction() {
        return $this->render(RoleRepository::userRolePermission(auth()->id()));
    }

    public function optionAction() {
        return $this->renderData(OptionRepository::get());
    }

    public function optionSaveAction(Input $input) {
        try {
            return $this->renderData(OptionRepository::save($input->get()));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}