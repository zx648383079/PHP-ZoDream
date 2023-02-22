<?php

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class TestWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return mixed
     * @throws Exception
     */
    public function render(PageWeightModel $model): string{
        return $this->show('view');
    }
}