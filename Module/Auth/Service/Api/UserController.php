<?php
namespace Module\Auth\Service\Api;

use Infrastructure\Uploader;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class UserController extends RestController {

    protected function rules() {
        return [
            'check' => '*',
            '*' => '@',
        ];
    }

    public function indexAction() {
        return $this->render(auth()->user());
    }

    /**
     * 上传用户头像
     * @return \Zodream\Infrastructure\Http\Output\RestResponse
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
     * @return \Zodream\Infrastructure\Http\Output\RestResponse
     * @throws \Exception
     */
    public function updateAction(Request $request) {
        $user = auth()->user();
        foreach (['name', 'sex', 'birthday'] as $key) {
            if (!$request->has($key)) {
                continue;
            }
            $value = $request->get($key);
            if (empty($value)) {
                continue;
            }
            $user->{$key} = $value;
        }
        $user->save();
        return $this->render($user);
    }

    /**
     * 验证邮箱是否注册
     * @param $email
     * @return \Zodream\Infrastructure\Http\Output\RestResponse
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

}