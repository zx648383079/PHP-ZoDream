<?php
namespace Module\Template\Domain\Themes;


use Module\Template\Domain\Pages\Page;
use Module\Template\Domain\Weights\Node;
use Module\Template\Domain\Weights\PageNode;

class Theme {

    const TYPE_HTML = 0;
    const TYPE_WX = 1;
    const TYPE_VUE = 2;
    const TYPE_REACT = 3;
    const TYPE_ANGULAR = 4;
    const TYPE_APK = 5;
    const TYPE_IPA = 6;
    const TYPE_UWP = 7;

    /**
     * @var Page[]
     */
    public $page_list = [];


    public function generate($type = self::TYPE_HTML) {
        return '';
    }

    public function hasNodeParser(PageNode $node) {
        return false;
    }

    public function generateNode(PageNode $node, $type) {
        return $node->generate($type);
    }
}