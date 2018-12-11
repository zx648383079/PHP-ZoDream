<?php
namespace Module\Template\Domain\Pages;


use Module\Template\Domain\Themes\Theme;
use Module\Template\Domain\Weights\Node;

class Page {

    /**
     * @var Theme
     */
    public $theme;

    /**
     * @var Node[]
     */
    public $node_list = [];

    public function generate($type) {

    }

}