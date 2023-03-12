<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;


use Module\Template\Domain\Model\SiteWeightModel;

interface IVisualWeight {

    /**
     * 输出内容
     * @param SiteWeightModel $model
     * @return string
     */
    public function render(SiteWeightModel $model): string;

    /**
     * 输出自定义表单
     * @param SiteWeightModel $model
     * @return string
     */
    public function renderForm(SiteWeightModel $model): string;

    /**
     * 转换自定义表单存进设置里
     * @return array
     */
    public function parseForm(): array;
}