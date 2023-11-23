<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Module\Template\Domain\VisualEditor\VisualHelper;
use Zodream\Helpers\Json;
use Module\Template\Domain\VisualEditor\VisualInput;

class ColumnAutoWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     */
    public function render(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        return $this->show('view', $data);
    }

    public function renderForm(SiteWeightModel $model): array {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        return [
            VisualInput::switch('split_mode', '启用平均分栏', $data['split_mode'] ?? 0)->attr('data-tab', 'split-tab'),
            VisualInput::number('split_count', '分栏数', $data['split_count']??'')->attr('tab', 'split_mode-0'),
            VisualInput::multiple('split_items', '分栏段数', $data['split_items'], [
                VisualInput::number('value', '比例', 2)
            ])->attr('tab', 'split_mode-1')
        ];
    }

    public function validateForm(array $input): array {
        $data = [];
        foreach($this->defaultData() as $key => $value) {
            $data[$key] = $input[$key] ?? $value;
        }
        return [
            'content' => Json::encode($data),
        ];
    }
    
    private function defaultData(): array {
        return [
            'split_mode' => 0,
            'split_count' => 2,
            'split_items' => [],
        ];
    }
}