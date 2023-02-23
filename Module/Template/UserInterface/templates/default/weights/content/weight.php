<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class ContentWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     * @throws Exception
     */
    public function render(SiteWeightModel $model): string {
        return $this->show('view');
    }
}