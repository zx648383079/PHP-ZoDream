<?php
namespace Module\Template\Domain\Weights;


use Module\Template\Domain\Pages\SinglePage;

class PageNode {

    /**
     * @var SinglePage
     */
    public $page;

    public $tag;

    /**
     * @var Node[]
     */
    public $children = [];

    public $content;

    public $attributes = [];

    public function generate($type) {
        if ($this->page->theme->hasNodeParser($this)) {
            return $this->page->theme->generateNode($this, $type);
        }
        return sprintf('<%s %s>%s</%s>', $this->tag, $this->generateAttribute($type), $this->generateContent($type), $this->tag);
    }

    public function generateAttribute($type) {
        $args = [];
        foreach ($this->attributes as $key => $attribute) {
            $args[] = sprintf('%s="%s"', $key, is_array($attribute)
                ? implode(' ', $attribute) : $attribute);
        }
        return implode(' ', $args);
    }


    public function generateContent($type) {
        if (!empty($this->content)) {
            return $this->content;
        }
        return implode('', array_map(function (Node $item) use ($type) {
            return $item->generate($type);
        }, $this->children));
    }

}