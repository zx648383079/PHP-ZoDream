<?php
namespace Module\Forum\Domain\Parsers;

use Module\Auth\Domain\Model\UserModel;
use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Template\Domain\Weights\Node;
use Zodream\Helpers\Str;

class AtNode extends Node {

    /**
     * @var Parser
     */
    protected $page;

    public function render($type = null) {
        $parent = intval($this->attr('parent'));
        $name = $this->attr('content');
        $html = $this->atPost($parent, $name);
        if ($html !== false) {
            return $html;
        }
        $user = UserModel::findByName($name);
        if (empty($user)) {
            return sprintf('@%s ', $name);
        }
        return $this->atUser($user->id, $name);
    }

    private function atUser($user, $content) {
        $thread = $this->page->getModel();
        $url = url('./thread', ['user' => $user, 'id' => $thread->thread_id]);
        return <<<HTML
<a href="{$url}#post-{$thread->id}">@{$content}</a> 
HTML;
    }

    private function atPost($id, $name) {
        if ($id < 1) {
            return false;
        }
        $model = ThreadPostModel::find($id);
        if (empty($model)) {
            return false;
        }
        $text = Str::substr($this->page->cleanText($model->content), 0, 100, true);
        $user = $this->atUser($model->user_id, $name);
        return <<<HTML
<div class="quote">
    <blockquote>
        {$user}
        <div class="quote-body">
            {$text}
        </div>
    </blockquote>
</div>
HTML;

    }
}