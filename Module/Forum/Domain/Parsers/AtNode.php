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

    protected bool $isJson = false;

    public function render($type = null) {
        $this->isJson = $type === 'json';
        $parent = intval($this->attr('parent'));
        $name = $this->attr('content');
        $html = $this->atPost($parent, $name);
        if ($html !== false) {
            return $html;
        }
        $user = UserModel::findByName($name);
        if (empty($user)) {
            return $this->noUser($name);
        }
        return $this->atUser($user->id, $name);
    }

    private function noUser(string $name) {
        if ($this->isJson) {
            return [
                'tag' => 'at',
                'content' => '@'.$name
            ];
        }
        return sprintf('@%s ', $name);
    }

    private function atUser($user, $content) {
        $thread = $this->page->getModel();
        if ($this->isJson) {
            return [
                'tag' => 'at',
                'content' => '@'.$content,
                'thread' => $thread->thread_id,
                'user' => $user,
                'post' => $thread->id
            ];
        }
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
            return $this->disableSee($user);
        }
        $text = Str::substr($this->page->cleanText($model->content), 0, 100, true);
        if ($this->isJson) {
            $user['text'] = $text;
            return $user;
        }
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

    private function disableSee($name) {
        if ($this->isJson) {
            $name['hide'] = false;
            return $name;
        }
        return <<<HTML
<div class="quote">
    <blockquote>
        {$name}
        <div class="quote-body">
            <div class="hide-locked-node">
                 <i class="fa fa-lock"></i> 本帖内容仅楼主可见
            </div>
        </div>
    </blockquote>
</div>
HTML;
    }
}