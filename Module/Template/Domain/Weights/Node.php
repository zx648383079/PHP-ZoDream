<?php
namespace Module\Template\Domain\Weights;

use Module\Template\Domain\Pages\IPage;

class Node implements INode {
    /**
     * @var IPage
     */
    protected $page;

    public function __construct(IPage $page) {
        $this->page = $page;
        $this->registerAsync();
    }

    protected function registerAsync() {

    }

    public function attr($key, $value = null) {
        // TODO: Implement attr() method.
    }

    public function render($type = null) {

        return '';
    }

    public function __toString() {
        return $this->render();
    }
}