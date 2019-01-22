<?php
namespace Module\Forum\Domain\Parsers;

use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Template\Domain\Weights\Node;

class HideNode extends Node {

    /**
     * @var Parser
     */
    protected $page;

    public function render($type = null) {
        if ($this->isHide()) {
            return $this->hideHtml();
        }
        $content = $this->attr('content');
        return <<<HTML
<div class="hide-toggle-node">
    {$content}
</div>
HTML;
    }

    private function hideHtml() {
        $name = auth()->guest() ? '游客' : auth()->user()->name;
        return <<<HTML
<div class="hide-locked-node">
    <i class="fa fa-lock"></i> {$name}，如果您要查看本帖隐藏内容请<a href="">回复</a>
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
        return ThreadPostModel::where('thread_id', $this->page->getModel()->thread_id)
            ->where('user_id', auth()->id())->count() < 1;
    }
}