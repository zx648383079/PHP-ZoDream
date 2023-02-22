<?php

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class Column2Weight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return mixed
     */
    public function render(PageWeightModel $model): string {
        return <<<HTML
<div data-type="weight" data-weight="column-2">
    <div>
    {$this->weight(1)}
    </div>
    <div>
    {$this->weight(2)}
    </div>
</div>
HTML;
    }
}