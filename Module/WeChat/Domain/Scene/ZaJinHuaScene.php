<?php
namespace Module\WeChat\Domain\Scene;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Game\ZaJinHua\Domain\Poker;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Module;

/**
 * 扎金花
 * @package Module\WeChat\Domain\Scene
 * @property integer $pool // 锅底
 * @property integer $min  // 最小暗注金额
 * @property array $system // 系统的牌
 * @property array $user // 玩家的牌
 * @property bool $see // 玩家是否看牌
 */
class ZaJinHuaScene extends BaseScene implements SceneInterface {

    public function enter() {
        if (Module::reply()->getUserId() < 1) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '请先绑定账户'
            ]);
        }
        $this->save();
        return $this->beginTip();
    }

    public function process($content) {
        if ($content == '余额') {
            return BalanceScene::balance();
        }
        if (in_array($content, ['退出', 'exit'])) {
            $this->leave();
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '您已退出游戏'
            ]);
        }
        return $this->play($content);
    }

    protected function play($content) {
        if (empty($this->pool)) {
            return $this->begin($content);
        }
        if (in_array($content, ['弃牌', '不要'])) {
            $this->clear();
            return $this->beginTip();
        }
        $system = $user = $this->min;
        if ($this->see) {
            $user *= 2;
        }
        if (in_array($content, ['跟', '跟注'])) {
            if (!$this->changeMoney($user, '跟注')) {
                return new ReplyModel([
                    'type' => ReplyModel::TYPE_TEXT,
                    'content' => '您的账户余额不足，请重新选择'
                ]);
            }
            $this->pool += $system + $user;
            return $this->playTip();
        }
        if (in_array($content, ['看', '看牌'])) {
            return $this->see();
        }
        if (in_array($content, ['加', '加注'])) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => sprintf('请输入您要加注的金额：(必须大于%d)', $user)
            ]);
        }
        if (in_array($content, ['比牌', '开', '开牌'])) {
            if (!$this->changeMoney($user * 2, '开牌')) {
                return new ReplyModel([
                    'type' => ReplyModel::TYPE_TEXT,
                    'content' => '您的账户余额不足，请重新选择'
                ]);
            }
            $this->pool += $user * 2;
            return $this->end();
        }
        if (!preg_match('/\d+/', $content, $match)) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => sprintf('请输入您要加注的金额：(必须大于%d)', $user)
            ]);
        }
        $money = intval($match[0]);
        if ($money < $user) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => sprintf('请输入您要加注的金额：(必须大于%d)', $user)
            ]);
        }
        if (!$this->changeMoney($money, '加注')) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '您的账户余额不足，请重新选择'
            ]);
        }
        $this->min = $this->see ? $money / 2 : $money;
        $this->pool += $money + $this->min;
        return $this->playTip();
    }

    public function end() {
        $game = new Poker();
        $tip = '您输了，请再接再厉';
        if (!$game->compare($this->system, $this->user)) {
            $tip = sprintf('恭喜，您获得了%s', $this->pool);
            $this->changeMoney($this->pool, '获胜');
        }
        $tip = sprintf('%s！您的牌为: %s，庄家的牌为：%s', $tip,
            implode('', $this->user), implode('', $this->system));
        $this->clear();
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => $tip
        ]);
    }

    protected function see() {
        $this->see = true;
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => sprintf('您的牌为: %s', implode('', $this->user))
        ]);
    }

    protected function begin($content) {
        $money = intval($content);
        if ($money <= 0) {
            return $this->beginTip();
        }
        if (!$this->changeMoney($this->pool, '加入底注')) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '您的账户余额不足，请重新选择'
            ]);
        }
        $game = new Poker();
        list($system, $user) = $game->assign($game->get());
        $data = [
            'pool' => $money * 2,
            'min' => $money,
            'system' => $system,
            'user' => $user,
            'see' => false
        ];
        $this->set($data);
        return $this->playTip();
    }

    protected function playTip() {
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => sprintf('总金额：%s，请选择：跟注/加注/比牌/弃牌/看牌', $this->pool)
        ]);
    }

    protected function beginTip() {
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '请输入底注：500/1000/2000'
        ]);
    }

    protected function changeMoney($money, $remark) {
        return AccountLogModel::change(Module::reply()->getUserId(),
            AccountLogModel::TYPE_GAME, 0, $money, '【炸金花】'.$remark, 1);
    }
}