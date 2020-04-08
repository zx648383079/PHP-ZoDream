<?php

use Module\Template\Service\BaseWeight;
use Module\Template\Domain\Model\PageWeightModel;

class ContentWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return mixed
     * @throws Exception
     */
    public function render(PageWeightModel $model){
        return $this->show('view', compact('model'));
    }
}