<?php
use Module\Template\Service\BaseWeight;
use Module\Template\Domain\Model\PageWeightModel;

class Column4Wight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param \Module\Template\Domain\Model\PageWeightModel $pageWeightModel
     * @return mixed
     */
    public function render(PageWeightModel $pageWeightModel){
        return <<<HTML
<div data-type="weight" data-weight="column-4">
    <div>
    {$this->weights($pageWeightModel->id, 1)}
    </div>
    <div>
    {$this->weights($pageWeightModel->id, 2)}
    </div>
    <div>
    {$this->weights($pageWeightModel->id, 3)}
    </div>
    <div>
    {$this->weights($pageWeightModel->id, 4)}
    </div>
</div>
HTML;
    }
}