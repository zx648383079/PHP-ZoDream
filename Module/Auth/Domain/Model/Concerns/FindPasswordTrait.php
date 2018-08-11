<?php
namespace Module\Auth\Domain\Model\Concerns;

trait FindPasswordTrait {

    public function findRules() {
        return [
            'code'     => 'validateFindCode',
            'password' => 'required|string:0,30',
            'rePassword' => 'validateRePassword',
        ];
    }

    public function validateFindCode($code) {
        if (empty($code)) {
            return false;
        }
        return true;
    }

    public function findPassword() {
        if (!$this->validate($this->findRules())) {
            return false;
        }
        /** @var $user static */
        $user = new static();
        $user->setPassword($this->password);
        return $user->save();
    }
}