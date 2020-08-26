<?php
namespace Module\Forum\Domain\Parsers;

use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Template\Domain\Pages\Page;
use Module\Template\Domain\Weights\INode;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Traits\SingletonPattern;

class Parser extends Page {

    use SingletonPattern;

    const UID_KEY = 'index';

    /**
     * @var ThreadPostModel
     */
    protected $model;

    private $uid = 0;
    /**
     * @var Request
     */
    public $request;

    protected function uid() {
        return $this->uid ++;
    }

    /**
     * @param ThreadPostModel $model
     * @return Parser
     */
    public function setModel(ThreadPostModel $model) {
        $this->model = $model;
        return $this;
    }

    /**
     * 判断是当前部件的请求
     * @param INode|int $uid
     * @param string $key
     * @return bool
     */
    public function isUnderAction($uid, $key = Parser::UID_KEY) {
        if (empty($this->request)) {
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
        $this->request = null;
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

    public function parse($html) {
        $this->uid = 0;
        if ($html instanceof ThreadPostModel) {
            $this->setModel($html);
            $html = $html->content;
        }
        return $this->parseText($html);
    }

    private function parseText($html) {
        $maps = [];
        foreach ($this->node_list as $node => $value) {
            $html = preg_replace_callback(
                sprintf('#<%s(\s+([^\<\>]+))?>([\s\S]*?)</%s>#i', $node, $node),
                function ($match) use ($node, &$maps) {
                    $attributes = $this->parseAttributes($match[2]);
                    $attributes[self::UID_KEY] = $this->uid();
                    $node = $this->node($node, $attributes);
                    $attributes['content'] = $node->isNest()
                        ? $this->parseText($match[3])
                        : htmlspecialchars($match[3]);
                    $cacheKey = md5(sprintf('%s-%s', Time::millisecond(), $attributes['index']));
                    $maps[$cacheKey] = $node->attr($attributes)->render();
                    return $cacheKey;
                }, $html);
        }
        $html = htmlspecialchars($html);
        $html = str_replace(["\n", "\t", ' '], ['<br/>', '&nbsp;&nbsp;&nbsp;&nbsp;', '&nbsp;'], $html);
        return str_replace(array_keys($maps), array_values($maps), $html);
    }

    public function cleanText($html) {
        foreach ($this->node_list as $node => $value) {
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
     * @param Request $request
     * @return string|string[]
     */
    public static function converterWithRequest(ThreadPostModel $model, Request $request) {
        $instance = new static();
        $instance->request = $request;
        return $instance->parse($model);
    }

    public static function converter($html) {
        return self::getInstance()->parse($html);
    }
}