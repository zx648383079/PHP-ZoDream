<?php

use Module\Template\Service\BaseWeight;
use Module\Template\Domain\Model\PageWeightModel;

class TabbarWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param \Module\Template\Domain\Model\PageWeightModel $pageWeightModel
     * @return mixed
     */
    public function render(PageWeightModel $pageWeightModel){
        return <<<HTML
<div data-type="weight" data-weight="tabbar">
    
</div>
HTML;
    }
}