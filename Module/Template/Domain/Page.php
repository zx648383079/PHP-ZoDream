<?php
namespace Module\Template\Domain;


use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Zodream\Infrastructure\Traits\Attributes;

class Page {

    use Attributes;

    protected $weights = [];

    /**
     * @var PageModel
     */
    protected $page;

    public function __construct($page) {
        $this->page = $page instanceof PageModel
            ? $page :
            PageModel::where('name', $page)->one();
    }

    /**
     * @param array $weights
     */
    public function setWeights($weights) {
        $this->weights = $weights;
    }

    public function addWeight($weight) {
        if (!is_array($weight)) {
            $weight = func_get_args();
        }
        $this->weights = array_merge($this->weights, $weight);
    }

    protected function loadWeights() {
        // 加载公共模块
        $this->addWeight(PageWeightModel::where('is_share', 1)->all());
        // 加载页面模块
        $this->addWeight($this->page->weights);
    }

    public function weight($parent_id, $ext) {

    }

    public function render() {

    }

    public function template() {

    }

}