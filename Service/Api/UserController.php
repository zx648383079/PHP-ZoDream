<?php
namespace Service\Api;

use Zodream\Domain\Filter\DataFilter;
use Zodream\Domain\Model\UserModel;

class UserController extends Controller {
    public function rules() {
        return [
            '*' => '*'
        ];
    }

    public function checkAction($user = null) {
        if (empty($user) || !DataFilter::validate($user, 'email')) {
            return $this->failure('4', '邮箱不合法');
        }
        $count = UserModel::count(['email' => $user]);
        return $this->success(boolval($count));
    }
}