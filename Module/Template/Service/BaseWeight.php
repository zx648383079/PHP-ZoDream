<?php
namespace Module\Template\Service;


use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Zodream\Infrastructure\Http\Request;

abstract class BaseWeight {
    /**
     * @var PageModel
     */
    protected $page;

    public function __construct(PageModel $pageModel) {
        $this->page = $pageModel;
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
}