<?php
namespace Module\Auth\Domain\Model\Concerns;

use Zodream\Helpers\Str;

trait RegisterTrait {

    public function signUpRules() {
        return [
            'name' => 'required|string:0,20',
            'email' => 'required|email',
            'password' => 'required|string:0,30',
            'rePassword' => 'validateRePassword',
            'agree' => ['validateAgree', 'message' => '必须同意相关协议！']
        ];
    }

    public function signUp() {
        if (!$this->validate($this->signUpRules())) {
            return false;
        }
        $user = $this->findByEmail($this->email);
        if (!empty($user)) {
            $this->setError('email', '邮箱已注册！');
            return false;
        }
        $this->setPassword($this->password);
        $this->created_at = time();
        $this->avatar = '/assets/images/avatar/'.Str::randomInt(0, 48).'.png';
        $this->sex = self::SEX_FEMALE;
        if (!$this->save()) {
            return false;
        }
        return $this->login();
    }
}