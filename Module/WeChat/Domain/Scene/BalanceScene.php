<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Scene;

/**
 * 查询用户信息
 * @package Module\WeChat\Domain\Scene
 */
class BalanceScene extends BaseScene implements SceneInterface {

    public function enter(string $content) {
        $user = $this->provider->authUser();
        if (empty($user)) {
            return $this->provider->renderReply('请先绑定账户');
        }
        return $this->provider->renderReply(
            sprintf('您的账户余额为 %s', $user->money)
        );
    }

    public function process($content) {
    }

}