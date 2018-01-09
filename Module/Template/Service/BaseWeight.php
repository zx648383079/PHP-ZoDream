<?php
namespace Module\Template\Service;

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Page;
use Zodream\Infrastructure\Http\Request;

abstract class BaseWeight {
    /**
     * @var Page
     */
    protected $page;

    /**
     * @param Page $page
     * @return BaseWeight
     */
    public function setPage($page) {
        $this->page = $page;
        return $this;
    }

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $pageWeightModel
     * @return mixed
     */
    abstract public function render(PageWeightModel $pageWeightModel);

    /**
     * 获取生成的配置视图
     * @param PageWeightModel $pageWeightModel
     * @return mixed
     */
    public function renderConfig(PageWeightModel $pageWeightModel) {
        return null;
    }

    public function parseConfigs() {
        return Request::request();
    }

    public function __call($name, $arguments) {
        return $this->page->{$name}(...$arguments);
    }
}