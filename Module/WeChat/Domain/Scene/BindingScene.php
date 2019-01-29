<?php
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Module;

/**
 * 绑定会员
 * @package Module\WeChat\Domain\Scene
 */
class BindingScene extends BaseScene implements SceneInterface {

    protected $name;

    protected $password;

    public function enter() {
        $content = Module::reply()->getMessage()->content;
        if (strpos($content, '解绑') !== false) {
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '解绑成功！'
            ]);
        }
        $this->save();
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '请输入账号'
        ]);
    }

    public function process($content) {
        if (empty($this->name)) {
            $this->name = $content;
            $this->save();
            return new ReplyModel([
                'type' => ReplyModel::TYPE_TEXT,
                'content' => '请输入密码'
            ]);
        }
        $this->password = $content;
        $this->leave();
        return new ReplyModel([
            'type' => ReplyModel::TYPE_TEXT,
            'content' => '绑定成功'
        ]);
    }
}