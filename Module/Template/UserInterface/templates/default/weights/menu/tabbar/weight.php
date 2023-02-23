<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class TabbarWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     */
    public function render(SiteWeightModel $model): string {
        return <<<HTML
<div data-type="weight" data-weight="tabbar">
    
</div>
HTML;
    }
}