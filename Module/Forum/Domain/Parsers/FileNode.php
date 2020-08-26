<?php
namespace Module\Forum\Domain\Parsers;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Forum\Domain\Error\StopNextException;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Template\Domain\Weights\Node;
use Zodream\Disk\File;
use Zodream\Helpers\Disk;
use Zodream\Service\Factory;

class FileNode extends Node {

    /**
     * @var Parser
     */
    protected $page;
    protected $price = 0;

    public function render($type = null) {
        $content = $this->attr('content');
        $this->price = floatval($this->attr('price'));
        /** @var File $file */
        $file = public_path($content);
        if (!$file->exist()) {
            return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> 【文件不存在】</p>
</div>
HTML;
        }
        $size = Disk::size($file->size());
        $count = ThreadLogModel::query()->where('item_type',
            ThreadLogModel::TYPE_THREAD_POST)
            ->where('item_id', $this->page->postId())
            ->where('action', ThreadLogModel::ACTION_DOWNLOAD)
            ->where('node_index', $this->attr(Parser::UID_KEY))->count();
        $url = url('./thread/do', ['id' => $this->page->postId(),
            Parser::UID_KEY => $this->attr(Parser::UID_KEY)], true, false);
        if (auth()->guest()) {
            $url = url('/auth', ['redirect_uri' => url()->to()]);
            return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> [<a href="{$url}">登录</a>]</p>
    <p>{$size}, 下载次数: {$count}</p>
</div>
HTML;
        }
        if (!$this->isBuy()) {
            return $this->buyTip($size, $count, $url);
        }
        if (!$this->page->isUnderAction($this)) {
            return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> [<a href="{$url}" target="_blank">下载</a>]</p>
    <p>{$size}, 下载次数: {$count}</p>
</div>
HTML;
        }
        Factory::response()->file($file);
        throw new StopNextException('下载文件');
    }

    private function buyTip($size, $count, $url) {
        return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> [<a data-action="confirm" 
    data-message="您将支付【{$this->price}】购买此处下载文件！" href="{$url}">购买({$this->price})</a>]</p>
    <p>{$size}, 下载次数: {$count}</p>
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
            ->where('action', ThreadLogModel::ACTION_DOWNLOAD)
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
                'action' => ThreadLogModel::ACTION_DOWNLOAD,
                'user_id' => auth()->id(),
                'node_index' => $this->attr(Parser::UID_KEY)
            ]);
        }, -$this->price, 0, '购买帖子下载内容');
        if (!$res) {
            throw new \Exception('支付失败，请检查您的账户余额');
            //return false;
        }
        $this->page->notUnderAction();
        return true;
    }
}