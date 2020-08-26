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
        /** @var ThreadPostModel $model */
        $model = ThreadPostModel::query()->where('id', $id)
        ->where('thread_id', $this->page->threadId())->first();
        if (empty($model)) {
            return false;
        }
        $model->setRelation('thread', $this->page->getModel()->thread);
        $user = $this->atUser($model->user_id, $name);
        if (!$model->is_public_post) {
            return <<<HTML
<div class="quote">
    <blockquote>
        {$user}
        <div class="quote-body">
            <div class="hide-locked-node">
                    <i class="fa fa-lock"></i> 本帖内容仅楼主可见
            </div>
        </div>
    </blockquote>
</div>
HTML;
        }
        $text = Str::substr($this->page->cleanText($model->content), 0, 100, true);
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