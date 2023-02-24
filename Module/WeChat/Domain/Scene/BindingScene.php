<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Scene;

use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\UserModel as WxUserModel;

/**
 * 绑定会员
 * @package Module\WeChat\Domain\Scene
 * @property string $name
 * @property string $password
 * @property string $failure // 失败次数
 */
class BindingScene extends BaseScene implements SceneInterface {

    public function enter(string $content) {
        if (str_contains($content, '解绑')) {
            return $this->unbinding();
        }
        if (!$this->canEnter()) {
            return $this->provider->renderReply('您已被禁止进行绑定，请24小时后重试');
        }
        if (!$this->checkBinding()) {
            return $this->provider->renderReply('您已绑定了其他账号，如需继续，请先解绑');
        }
        $this->save();
        return $this->provider->renderReply('请输入账号');
    }

    public function process(string $content) {
        if (in_array($content, ['退出', 'exit'])) {
            $this->leave();
            return $this->provider->renderReply('您已终止了绑定操作');
        }
        return $this->bindingStep($content);
    }

    private function bindingStep(string $content) {
        if ($this->failure > 5) {
            $this->leave();
            $this->disableEnter();
            return $this->provider->renderReply('失败次数太多了，请24小时后再试');
        }
        if (empty($this->name)) {
            return $this->checkName($content);
        }
        $user = UserModel::findByAccount($this->name, $content);
        if (empty($user)) {
            $this->failure ++;
            return $this->provider->renderReply('您输入的密码错误');
        }
        $openid = $this->provider->fromId();
        $nickname = WxUserModel::where('openid', $openid)->value('nickname');
        $auth = OAuthModel::bindUser($user, $openid, '',
            $this->provider->oAuthType(), $nickname.'');
        $this->leave();
        return $this->provider->renderReply(empty($auth) ? '绑定失败，请重试' : '绑定成功');
    }

    private function checkName(string $name) {
        $user = UserModel::findByEmail($name);
        if (empty($user)) {
            $this->failure ++;
            return $this->provider->renderReply('账号不存在');
        }
        $this->name = $name;
        return $this->provider->renderReply('请输入密码');
    }

    /**
     * 判断是否已经绑定了其他账号
     * @return bool
     * @throws \Exception
     */
    private function checkBinding() {
        return $this->provider->authUserId() < 1;
    }

    private function unbinding() {
        $openid = $this->provider->fromId();
        $auth = OAuthModel::findUser(
            $openid, $this->provider->oAuthType());
        if (empty($auth)) {
            return $this->provider->renderReply('您的微信未绑定账号！');
        }
        $auth->delete();
        return $this->provider->renderReply('解绑成功！');
    }

    private function failureId() {
        return sprintf('wx_failure_%s_%s',
            static::class, $this->provider->fromId());
    }

    private function canEnter() {
        return !cache()->has($this->failureId());
    }

    private function disableEnter() {
        return cache()->set($this->failureId(), $this->provider->fromId(), 86400);
    }
}