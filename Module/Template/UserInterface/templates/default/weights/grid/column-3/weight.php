<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class Column3Weight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     */
    public function render(SiteWeightModel $model): string {
        return <<<HTML
<div class="weight-row" data-type="weight" data-weight="column-3">
    <div class="col-1">
    {$this->weight(1)}
    </div>
    <div class="col-1">
    {$this->weight(2)}
    </div>
    <div class="col-1">
    {$this->weight(3)}
    </div>
</div>
HTML;
    }
}