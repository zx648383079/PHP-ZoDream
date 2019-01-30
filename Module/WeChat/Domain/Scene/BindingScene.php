<?php
namespace Module\WeChat\Domain\Scene;

use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Module;
use Module\WeChat\Domain\Model\UserModel as WxUserModel;

/**
 * 绑定会员
 * @package Module\WeChat\Domain\Scene
 * @property string $name
 * @property string $password
 * @property string $failure // 失败次数
 */
class BindingScene extends BaseScene implements SceneInterface {

    public function enter() {
        $content = Module::reply()->getMessage()->content;
        if (strpos($content, '解绑') !== false) {
            return $this->unbinding();
        }
        if (!$this->canEnter()) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '您已被禁止进行绑定，请24小时后重试'
            ]);
        }
        $this->save();
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '请输入账号'
        ]);
    }

    public function process($content) {
        if (in_array($content, ['退出', 'exit'])) {
            $this->leave();
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '您已终止了绑定操作'
            ]);
        }
        return $this->bindingStep($content);
    }

    private function bindingStep($content) {
        if ($this->failure > 5) {
            $this->leave();
            $this->disableEnter();
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '失败次数太多了，请24小时后再试'
            ]);
        }
        if (empty($this->name)) {
            return $this->checkName($content);
        }
        $user = UserModel::findByAccount($this->name, $content);
        if (empty($user)) {
            $this->failure ++;
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '您输入的密码错误'
            ]);
        }
        $openid = Module::reply()->getOpenId();
        $nickname = WxUserModel::where('openid', $openid)->value('nickname');
        OAuthModel::bindUser($user, $openid,
            OAuthModel::TYPE_WX, $nickname);
        $this->leave();
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '绑定成功'
        ]);
    }

    private function checkName($name) {
        $user = UserModel::findByName($name);
        if (empty($user)) {
            $this->failure ++;
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '账号不存在'
            ]);
        }
        $this->name = $name;
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '请输入密码'
        ]);
    }

    private function unbinding() {
        $openid = Module::reply()->getOpenId();
        $auth = OAuthModel::findUser(
            $openid, OAuthModel::TYPE_WX);
        if (empty($auth)) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '您的微信未绑定账号！'
            ]);
        }
        $auth->delete();
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '解绑成功！'
        ]);
    }

    private function failureId() {
        return sprintf('wx_failure_%s_%s',
            static::class, Module::reply()->getOpenId());
    }

    private function canEnter() {
        return cache()->has($this->failureId());
    }

    private function disableEnter() {
        return cache()->set($this->failureId(), Module::reply()->getOpenId(), 86400);
    }
}