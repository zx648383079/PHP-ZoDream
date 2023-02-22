<?php

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class TabbarWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return mixed
     */
    public function render(PageWeightModel $model): string {
        return <<<HTML
<div data-type="weight" data-weight="tabbar">
    
</div>
HTML;
    }
}