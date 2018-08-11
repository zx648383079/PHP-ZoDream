<?php
namespace Module\Auth\Domain\Model\Concerns;

trait PasswordTrait {

    public function resetRules() {
        return [
            'oldPassword'     => 'required|string:0,30',
            'password' => 'required|string:0,30',
            'rePassword' => 'validateRePassword',
        ];
    }

    public function resetPassword() {
        if (!$this->validate($this->resetRules())) {
            return false;
        }
        /** @var $user static */
        $user = auth()->user();
        if (!$user->validatePassword($this->password)) {
            return false;
        }
        $user->setPassword($this->password);
        return $user->save();
    }
}