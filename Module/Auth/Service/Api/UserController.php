<?php
namespace Module\Auth\Service\Api;

use Infrastructure\Uploader;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class UserController extends Controller {

    public function rules() {
        return [
            'check' => '*',
            '*' => '@',
        ];
    }

    public function indexAction() {
        $data = UserRepository::getCurrentProfile();
        return $this->render($data);
    }

    /**
     * 上传用户头像
     * @throws \Exception
     */
    public function avatarAction() {
        $upload = new Uploader('file', [
            'pathFormat' => '/assets/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'maxSize' => 2048000,
            'allowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp']
        ]);
        $data = $upload->getFileInfo();
        if ($data['state'] !== 'SUCCESS') {
            return $this->renderFailure($data['state']);
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
            $user = AuthRepository::updateProfile($request);
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
    public function checkAction($email) {
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

}