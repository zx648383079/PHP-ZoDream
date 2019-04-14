<?php
namespace Module\WeChat\Domain\Scene;

use Module\Auth\Domain\Model\Game\CheckInModel;
use Module\WeChat\Domain\Game\ZaJinHua;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Module;

/**
 * 每日签到
 * @package Module\WeChat\Domain\Scene
 * @property integer $pool // 锅底
 * @property integer $min  // 最小暗注金额
 * @property array $system // 系统的牌
 * @property array $user // 玩家的牌
 * @property bool $see // 玩家是否看牌
 */
class ZaJinHuaScene extends BaseScene implements SceneInterface {

    public function enter() {
        $this->save();
        return $this->beginTip();
    }

    public function process($content) {
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
        $this->min = $this->see ? $money / 2 : $money;
        $this->pool += $money + $this->min;
        return $this->playTip();
    }

    public function end() {
        $game = new ZaJinHua();
        $tip = '您输了，请再接再厉';
        if (!$game->compare($this->system, $this->user)) {
            $tip = sprintf('恭喜，您获得了%s', $this->pool);
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
        $game = new ZaJinHua();
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
}