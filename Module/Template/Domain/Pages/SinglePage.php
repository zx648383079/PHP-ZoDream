<?php
namespace Module\Template\Domain\Pages;


use Module\Template\Domain\Themes\Theme;
use Module\Template\Domain\Weights\PageNode;

class SinglePage {

    /**
     * @var Theme
     */
    public $theme;

    /**
     * @var PageNode[]
     */
    public $node_list = [];

    public function generate($type) {

    }

}