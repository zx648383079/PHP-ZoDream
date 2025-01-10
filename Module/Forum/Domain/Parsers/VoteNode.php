<?php
namespace Module\Forum\Domain\Parsers;

use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Template\Domain\Weights\Node;

/**
 * 投票
 * @package Module\Forum\Domain\Parsers
 */
class VoteNode extends Node {

    const ITEM_REGEX = '#<v>(.+?)</v>#';

    /**
     * @var Parser
     */
    protected $page;
    protected int $max = 1;
    protected bool $isJson = false;

    public function render(string $type = ''): mixed {
        $this->isJson = $type === 'json';
        $content = htmlspecialchars_decode($this->attr('content'));
        $this->max = max(intval($this->attr('max')), 1);
        if ($this->isShowForm()) {
            return $this->isJson ? $this->formatJson($content) : $this->formHtml($content);
        }
        $subtotal = $this->getSubtotal();
        if ($this->isJson) {
            return $this->formatJson($content, $subtotal);
        }
        $content = $this->replace($content, function ($label, $i) use ($subtotal) {
            $tip = '0/0';
            $width = '0';
            if (!empty($subtotal)) {
                $count = $subtotal['items'][$i] ?? 0;
                $tip = sprintf('%d/%d', $count, $subtotal['total']);
                $width = number_format($count * 100 / $subtotal['total'], 2);
            }
            return <<<HTML
<div class="vote-item">
    <span>{$label}</span>
    <div class="vote-progress"  title="{$tip}">
        <div class="vote-value" style="width: {$width}%;">{$tip}</div>
    </div>
</div>
HTML;
        });
        return <<<HTML
<div class="vote-box">
    {$content}    
</div>
HTML;
    }

    private function replace($content, callable $cb) {
        $i = 0;
        return preg_replace_callback(self::ITEM_REGEX, function ($match) use (&$i, $cb) {
            return call_user_func($cb, $match[1], $i ++);
        }, $content);
    }

    private function getSubtotal() {
        $data = ThreadLogModel::query()->where('item_type', ThreadLogModel::TYPE_THREAD_POST)
            ->where('item_id', $this->page->postId())
            ->where('action', ThreadLogModel::ACTION_VOTE)
            ->where('node_index', $this->attr(Parser::UID_KEY))
            ->groupBy('data')
            ->select('data as i, COUNT(data) as count')->asArray()->get();
        if (empty($data)) {
            return null;
        }
        $items = array_column($data, 'count', 'i');
        $total = array_sum($items);
        return compact('items', 'total');
    }

    private function isShowForm() {
        if (auth()->guest()) {
            return false;
        }
        if ($this->page->getModel()->thread->is_closed) {
            return false;
        }
        $count = ThreadLogModel::query()->where('item_type', ThreadLogModel::TYPE_THREAD_POST)
            ->where('item_id', $this->page->postId())
            ->where('action', ThreadLogModel::ACTION_VOTE)
            ->where('user_id', auth()->id())
            ->where('node_index', $this->attr(Parser::UID_KEY))->count();
        if ($count > 0) {
            return false;
        }
        if (!$this->page->isUnderAction($this)) {
            return true;
        }
        if (!$this->page->request->has('data')) {
            throw new \Exception('请选择投票项');
        }
        $data = $this->page->request->get('data');
        if ($this->max <= 1) {
            ThreadLogModel::create([
                'item_type' => ThreadLogModel::TYPE_THREAD_POST,
                'item_id' => $this->page->postId(),
                'action' => ThreadLogModel::ACTION_VOTE,
                'user_id' => auth()->id(),
                'node_index' => $this->attr(Parser::UID_KEY),
                'data' => intval($data)
            ]);
            return false;
        }
        $items = [];
        foreach ((array)$data as $i) {
            $items[] = [
                'item_type' => ThreadLogModel::TYPE_THREAD_POST,
                'item_id' => $this->page->postId(),
                'action' => ThreadLogModel::ACTION_VOTE,
                'user_id' => auth()->id(),
                'node_index' => $this->attr(Parser::UID_KEY),
                'data' => intval($i)
            ];
        }
        ThreadLogModel::query()->insert($items);
        return false;
    }


    private function formatJson(string $content, array|null $subtotal = []): array {
        $index = $this->attr(Parser::UID_KEY);
        $items = [];
        $content = $this->replace($content, function ($label, $i) use ($index, &$items, $subtotal) {
            $count = $subtotal['items'][$i] ?? 0;
            $percentage = $count < 1 ? 0 : ($count * 100 / $subtotal['total']);
            $items[] = [
                'i' => $i,
                'no' => $i + 1,
                'id' => sprintf('vote_%d_%d_%d', $this->page->postId(), $index, $i),
                'value' => $label,
                'count' => $count,
                'percentage' => str_contains((string)$percentage, '.') ? number_format($percentage, 2) : $percentage,
            ];
            return '';
        });
        return [
            'tag' => 'vote',
            'content' => $content,
            'items' => $items,
            'max' => $this->max,
            'editable' => is_array($subtotal) && empty($subtotal),
            'total' => empty($subtotal) ? 0  : $subtotal['total'],
        ];
    }

    private function formHtml($content) {
        $index = $this->attr(Parser::UID_KEY);
        $action = url('./thread/do', ['id' => $this->page->postId(),
            Parser::UID_KEY => $index], true, false);
        $content = $this->replace($content, function ($label, $i) use ($index) {
            $id = sprintf('vote_%d_%d_%d', $this->page->postId(), $index, $i);
            if ($this->max <= 1) {
                return <<<HTML
<div class="vote-item">
    <input type="radio" name="data" id="{$id}" value="{$i}">
    <label for="{$id}">{$label}</label>
</div>
HTML;
            }
            return <<<HTML
<div class="vote-item">
    <input type="checkbox" name="data[]" id="{$id}" value="{$i}">
    <label for="{$id}">{$label}</label>
</div>
HTML;
        });

        return <<<HTML
<form class="vote-box" data-action="ajax" action="{$action}" method="POST">
    {$content}
    <button>提交</button>
</form>
HTML;
    }
}