<?php

use Module\Template\Service\BaseWeight;
use Module\Template\Domain\Model\PageWeightModel;

class TestWight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return mixed
     * @throws Exception
     * @throws \Zodream\Disk\FileException
     */
    public function render(PageWeightModel $model){
        return $this->page->getFactory()->render('view', $model);
    }
}