<?php
namespace Module\Forum\Domain\Parsers;

use Module\Forum\Domain\Model\ThreadPostModel;
use Module\Template\Domain\Pages\Page;
use Zodream\Infrastructure\Traits\SingletonPattern;

class Parser extends Page {

    use SingletonPattern;

    /**
     * @var ThreadPostModel
     */
    protected $model;

    /**
     * @param ThreadPostModel $model
     * @return Parser
     */
    public function setModel(ThreadPostModel $model) {
        $this->model = $model;
        return $this;
    }

    /**
     * @return ThreadPostModel
     */
    public function getModel(): ThreadPostModel {
        return $this->model;
    }

    protected function loadNodes() {
        $data = [
            'hide' => HideNode::class,
            'file' => FileNode::class
        ];
        foreach ($data as $key => $item) {
            $this->register($key, $item);
        }
    }

    public function parse($html) {
        if ($html instanceof ThreadPostModel) {
            $this->setModel($html);
            $html = $html->content;
        }
        $i = 0;
        foreach ($this->node_list as $node => $value) {
            $html = preg_replace_callback(
                sprintf('#<%s(\s+([^\<\>]+))?>([\s\S]*?)</%s>#i', $node, $node),
                function ($match) use ($node, &$i) {
                    $attributes = $this->parseAttributes($match[2]);
                    $attributes['index'] = $i ++;
                    $attributes['content'] = $match[3];
                return $this->node($node, $attributes)->render();
            }, $html);
        }
        return $html;
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

    public static function converter($html) {
        return self::getInstance()->parse($html);
    }
}