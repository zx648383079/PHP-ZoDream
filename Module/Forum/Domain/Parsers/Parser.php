<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Template\Domain\Pages\Page;
use Module\Template\Domain\Weights\INode;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Concerns\SingletonPattern;

class Parser extends Page {

    use SingletonPattern;

    const UID_KEY = 'index';

    /**
     * @var ThreadPostModel
     */
    protected $model;

    private int $uid = 0;
    /**
     * @var Input
     */
    public Input $request;

    protected string $content = '';

    protected bool $openNotUnder = false;

    protected function uid(): int {
        return $this->uid ++;
    }

    /**
     * @param ThreadPostModel $model
     * @return Parser
     */
    public function setModel(ThreadPostModel $model) {
        $this->model = $model;
        if (empty($this->content)) {
            $this->content = $model->content;
        }
        return $this;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content) {
        $this->content = $content;
        return $this;
    }

    /**
     * 判断是当前部件的请求
     * @param INode|int $uid
     * @param string $key
     * @return bool
     */
    public function isUnderAction($uid, $key = Parser::UID_KEY) {
        if (empty($this->request) || $this->openNotUnder) {
            return false;
        }
        if (!$this->request->has($key)) {
            return false;
        }
        if ($uid instanceof INode) {
            $uid = $uid->attr(self::UID_KEY);
        }
        return intval($this->request->get($key)) === $uid;
    }

    /**
     * 执行完了，可以清除了
     */
    public function notUnderAction() {
        $this->openNotUnder = true;
    }

    /**
     * @return ThreadPostModel
     */
    public function getModel(): ThreadPostModel {
        return $this->model;
    }

    /**
     * 主题id
     * @return int
     */
    public function threadId() {
        return $this->getModel()->thread_id;
    }

    /**
     * 回帖id
     * @return int
     */
    public function postId() {
        return $this->getModel()->id;
    }

    protected function loadNodes() {
        $data = [
            EmojiNode::class,
            PageNode::class,
            'hide' => HideNode::class,
            'file' => FileNode::class,
            'code' => CodeNode::class,
            'a' => LinkNode::class,
            'img' => ImgNode::class,
            'video' => VideoNode::class,
            'audio' => AudioNode::class,
            'at' => AtNode::class,
            'vote' => VoteNode::class,
        ];
        foreach ($data as $key => $item) {
            $this->register($key, $item);
        }
    }

    public function render($type = null)
    {
        $this->uid = 0;
        return $type !== 'json' ? $this->parseText($this->content, $type) : $this->parseJson($this->content, $type);
    }

    private function parseText($html, $type) {
        $maps = [];
        $this->eachNode(function (INode $node) use (&$html, $type) {
            $html = $node->attr('content', $html)->render($type);
        }, true);
        $this->eachNode(function (INode $nodeInstance, $node) use (&$html, &$maps, $type) {
            $html = preg_replace_callback(
                sprintf('#<%s(\s+([^\<\>]+))?>([\s\S]*?)</%s>#i', $node, $node),
                function ($match) use ($nodeInstance, &$maps, $type) {
                    $attributes = $this->parseAttributes($match[2]);
                    $attributes[self::UID_KEY] = $this->uid();
                    $attributes['content'] = $nodeInstance->isNest()
                        ? $this->parseText($match[3], $type)
                        : htmlspecialchars($match[3]);
                    $cacheKey = md5(sprintf('%s-%s', Time::millisecond(), $attributes['index']));
                    $maps[$cacheKey] = $nodeInstance->attr($attributes)->render($type);
                    return $cacheKey;
                }, $html);
        }, false);
        $html = htmlspecialchars($html);
        $html = str_replace(["\n", "\t", ' '], ['<br/>', '&nbsp;&nbsp;&nbsp;&nbsp;', '&nbsp;'], $html);
        return str_replace(array_keys($maps), array_values($maps), $html);
    }

    private function parseJson(string $content, string $type) {
        $extraRule = [];
        $this->eachNode(function (INode $node) use (&$content, &$extraRule, $type) {
            $data = $node->attr('content', $content)->render($type);
            $content = $data['content'];
            if (isset($data['extra_rule'])) {
                $extraRule = array_merge($extraRule, $data['extra_rule']);
            }
        }, true);
        $this->eachNode(function (INode $node, $name) use (&$content, &$extraRule, $type) {
            $content = preg_replace_callback(
                sprintf('#<%s(\s+([^\<\>]+))?>([\s\S]*?)</%s>#i', $name, $name),
                function ($match) use ($node, &$extraRule, $type) {
                    $attributes = $this->parseAttributes($match[2]);
                    $attributes[self::UID_KEY] = $this->uid();
                    $attributes['content'] = $node->isNest()
                        ? $this->parseJson($match[3], $type)
                        : $match[3];
                    $cacheKey = sprintf('<tag:%s-%s>', Time::millisecond(), $attributes['index']);
                    $extraRule[] = [
                        's' => $cacheKey,
                        'uid' => $attributes['index'],
                        'custom' => $node->attr($attributes)->render($type)
                    ];
                    return $cacheKey;
                }, $content);
        }, false);
        return [
            'content' => $content,
            'extra_rule' => $extraRule
        ];
    }

    public function cleanText($html) {
        foreach ($this->nodeItems as $node => $value) {
            $html = preg_replace(
                sprintf('#<%s(\s+([^\<\>]+))?>([\s\S]*?)</%s>#i', $node, $node),
                '', $html);
        }
        return htmlspecialchars($html);
    }

    private function parseAttributes($attr) {
        if (empty($attr)) {
            return [];
        }
        $matches = [];
        if (preg_match_all('#([\w_-]+)="?([^"]+)#', $attr, $matches, PREG_SET_ORDER)) {
            return array_column($matches, 2, 1);
        }
        return [];
    }

    /**
     * 转换并处理请求
     * @param ThreadPostModel $model
     * @param Input $request
     * @return static
     */
    public static function create(ThreadPostModel $model, Input $request) {
        $instance = new static();
        $instance->request = $request;
        $instance->setModel($model);
        return $instance;
    }
}