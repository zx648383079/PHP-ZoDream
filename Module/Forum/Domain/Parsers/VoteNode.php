<?php
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;
use Zodream\Helpers\Str;

/**
 * 投票
 * @package Module\Forum\Domain\Parsers
 */
class VoteNode extends Node {

    const ITEM_REGEX = '#<v>(\.+?)</v>#';

    /**
     * @var Parser
     */
    protected $page;

    public function render($type = null) {
        $content = $this->attr('content');

        return '<div class="vote-box">
        这是说明
        <div class="vote-item">
            <span>选项一</span>
            <div class="vote-progress"  title="1/100">
                <div class="vote-value">1/100</div>
            </div>
        </div>
        <div class="vote-item">
            <span>选项一</span>
            <div class="vote-progress" title="1/100">
                <div class="vote-value" style="width: 10%;">1/100</div>
            </div>
        </div>
    </div>';
    }

    private function formHtml() {
        return '<form class="vote-box" action="" method="POST">
        这是说明
        <div class="vote-item">
            <input type="checkbox" name="" id="">
            <label for="">选项一</label>
        </div>
        <button>提交</button>
    </form>';
    }
}