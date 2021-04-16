<?php
declare(strict_types=1);
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
    public array $nodeItems = [];

    public function generate($type) {

    }

}