<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Module\Template\Domain\VisualEditor\VisualHelper;
use Zodream\Helpers\Json;

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

    public function renderForm(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        $data['split_items'] = implode(',', $data['split_items']);
        return $this->show('config', $data);
    }

    public function parseForm(): array {
        $request = request();
        $data = [];
        foreach($this->defaultData() as $key => $value) {
            $data[$key] = $request->get($key, $value);
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