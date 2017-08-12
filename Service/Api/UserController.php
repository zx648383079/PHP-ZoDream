<?php
namespace Service\Api;

use Zodream\Domain\Filter\DataFilter;
use Zodream\Database\Model\UserModel;

class UserController extends Controller {
    public function rules() {
        return [
            '*' => '*'
        ];
    }

    public function checkAction($email = null) {
        if (empty($email) || !DataFilter::validate($email, 'email')) {
            return $this->failure('4', '邮箱不合法');
        }
        $count = UserModel::count(['email' => $email]);
        return $this->success(boolval($count));
    }

    public function loginAction() {
        return $this->success([
            'username' => 'admin',
            'token' => '11111',
            'avatar' => 'Nasta'
        ]);
    }
}