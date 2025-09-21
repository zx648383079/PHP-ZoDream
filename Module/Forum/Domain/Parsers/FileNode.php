<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Forum\Domain\Error\StopNextException;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Template\Domain\Weights\Node;
use Zodream\Disk\File;
use Zodream\Helpers\Disk;

class FileNode extends Node {

    /**
     * @var Parser
     */
    protected $page;
    protected float $price = 0;
    protected bool $isJson = false;

    public function render(string $type = ''): mixed {
        $this->isJson = $type === 'json';
        $content = $this->attr('content');
        $this->price = floatval($this->attr('price'));
        if (str_contains($content, '//')) {
            $content = parse_url($content, PHP_URL_PATH);
        }
        /** @var File $file */
        $file = public_path($content);
        if (!$file->exist()) {
            return $this->fileNotFound();
        }
        $size = $file->size();
        $count = ThreadLogModel::query()->where('item_type',
            ThreadLogModel::TYPE_THREAD_POST)
            ->where('item_id', $this->page->postId())
            ->where('action', ThreadLogModel::ACTION_DOWNLOAD)
            ->where('node_index', $this->attr(Parser::UID_KEY))->count();
        $url = url('./thread/do', ['id' => $this->page->postId(),
            Parser::UID_KEY => $this->attr(Parser::UID_KEY)], true, false);
        if (auth()->guest()) {
            return $this->loginTip($count, $size);
        }
        if (!$this->isBuy()) {
            return $this->buyTip($size, $count, $url, $file->getName());
        }
        if (!$this->page->isUnderAction($this)) {
            return $this->downloadBtn($url, $count, $size, $file->getName());
        }
        response()->file($file);
        throw new StopNextException('下载文件');
    }

    private function downloadBtn($url, $count, $size, string $fileName) {
        if ($this->isJson) {
            return [
                'tag' => 'file',
                'count' => $count,
                'size' => $size,
                'name' => $fileName
            ];
        }
        $size = Disk::size($size);
        return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> [<a href="{$url}" target="_blank">下载</a>]</p>
    <p>{$size}, 下载次数: {$count}</p>
</div>
HTML;
    }

    private function loginTip(int $count, int $size) {
        if ($this->isJson) {
            return [
                'tag' => 'file_login',
                'count' => $count,
                'size' => $size
            ];
        }
        $url = url('/auth', ['redirect_uri' => url()->full()]);
        $size = Disk::size($size);
        return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> [<a href="{$url}">登录</a>]</p>
    <p>{$size}, 下载次数: {$count}</p>
</div>
HTML;
    }

    private function fileNotFound() {
        if ($this->isJson) {
            return [
                'tag' => 'file',
                'error' => true
            ];
        }
        return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> 【文件不存在】</p>
</div>
HTML;
    }

    private function buyTip($size, $count, $url, string $fileName) {
        $confirm = sprintf('您将支付【%d】购买此处下载文件！', $this->price);
        if ($this->isJson) {
            return [
                'tag' => 'file_buy',
                'price' => $this->price,
                'count' => $count,
                'size' => $size,
                'confirm' => $confirm,
                'name' => $fileName
            ];
        }
        $size = Disk::size($size);
        return <<<HTML
<div class="file-down-node">
    <p><i class="fa fa-file-archive"></i> [<a data-action="confirm" 
    data-message="{$confirm}" href="{$url}">购买({$this->price})</a>]</p>
    <p>{$size}, 下载次数: {$count}</p>
</div>
HTML;
    }

    private function isBuy() {
        if ($this->page->isReviewMode) {
            return true;
        }
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
        $res = FundAccount::payTo(auth()->id(), FundAccount::TYPE_FORUM_BUY, function () {
            return ThreadLogModel::create([
                'item_type' => ThreadLogModel::TYPE_THREAD_POST,
                'item_id' => $this->page->postId(),
                'action' => ThreadLogModel::ACTION_DOWNLOAD,
                'user_id' => auth()->id(),
                'node_index' => $this->attr(Parser::UID_KEY)
            ]);
        }, -$this->price, '购买帖子下载内容', $this->page->getModel()->user_id);
        if (!$res) {
            throw new \Exception('支付失败，请检查您的账户余额');
            //return false;
        }
        $this->page->notUnderAction();
        return true;
    }
}