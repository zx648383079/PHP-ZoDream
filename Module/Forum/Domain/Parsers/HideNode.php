<?php
namespace Module\Forum\Domain\Parsers;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Template\Domain\Weights\Node;

class HideNode extends Node {

    /**
     * @var Parser
     */
    protected $page;
    protected float $price = 0;
    protected bool $isJson = false;

    public function isNest(): bool {
        return true;
    }

    public function render($type = null) {
        $this->isJson = $type === 'json';
        $this->price = floatval($this->attr('price'));
        if ($this->isHide()) {
            return $this->hideHtml();
        }
        if (!$this->isBuy()) {
            return $this->buyTip();
        }
        $content = $this->attr('content');
        if ($this->isJson) {
            return [
                'tag' => 'hide',
                'content' => $content
            ];
        }
        return <<<HTML
<div class="hide-open-node">
    <div class="node-tip">本帖隐藏的内容</div>
    {$content}
</div>
HTML;
    }

    private function isBuy() {
        if ($this->price <= 0) {
            return true;
        }
        if (auth()->id() == $this->page->getModel()->user_id) {
            return true;
        }
        $count = ThreadLogModel::query()->where('item_type', ThreadLogModel::TYPE_THREAD_POST)
            ->where('item_id', $this->page->postId())
            ->where('action', ThreadLogModel::ACTION_BUY)
            ->where('user_id', auth()->id())
            ->where('node_index', $this->attr(Parser::UID_KEY))->count();
        if ($count > 0) {
            return true;
        }
        if (!$this->page->isUnderAction($this)) {
            return false;
        }
        $res = AccountLogModel::changeAsync(auth()->id(), AccountLogModel::TYPE_FORUM_BUY, function () {
            return ThreadLogModel::create([
                'item_type' => ThreadLogModel::TYPE_THREAD_POST,
                'item_id' => $this->page->postId(),
                'action' => ThreadLogModel::ACTION_BUY,
                'user_id' => auth()->id(),
                'node_index' => $this->attr(Parser::UID_KEY)
            ]);
        }, -$this->price, '购买帖子隐藏内容');
        if (!$res) {
            throw new \Exception('支付失败，请检查您的账户余额');
            //return false;
        }
        return true;
    }

    private function buyTip() {
        $name = auth()->user()->name;
        $confirm = sprintf('您将支付【%d】查看此处隐藏内容！', $this->price);
        if ($this->isJson) {
            return [
                'tag' => 'hide_buy',
                'price' => $this->price,
                'name' => $name,
                'confirm' => $confirm
            ];
        }
        $url = url('./thread/do', ['id' => $this->page->postId(),
            Parser::UID_KEY => $this->attr(Parser::UID_KEY)], true, false);
        return <<<HTML
<div class="hide-locked-node">
    <i class="fa fa-lock"></i> {$name}，如果您要查看本帖隐藏内容请<a data-action="confirm" 
    data-message="{$confirm}" href="{$url}">购买[{$this->price}]</a>
</div>
HTML;
    }

    private function hideHtml() {
        $name = auth()->guest() ? '游客' : auth()->user()->name;
        if ($this->isJson) {
            return [
                'tag' => 'hide',
                'name' => $name
            ];
        }
        $url = auth()->guest() ? url('/auth', ['redirect_uri' => url()->full()])
            : 'javascript:;';
        return <<<HTML
<div class="hide-locked-node">
    <i class="fa fa-lock"></i> {$name}，如果您要查看本帖隐藏内容请<a href="{$url}">回复</a>
</div>
HTML;
    }

    private function isHide() {
        if (auth()->guest()) {
            return true;
        }
        if (auth()->id() == $this->page->getModel()->user_id) {
            return false;
        }
        return ThreadPostModel::where('thread_id', $this->page->threadId())
            ->where('user_id', auth()->id())->count() < 1;
    }
}