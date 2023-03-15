<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Zodream\Helpers\Json;
use Module\Template\Domain\VisualEditor\VisualHelper;

class SearchBarWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     */
    public function render(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        if ($data['search_type'] === 1) {
            $data['search_weight_id'] = VisualHelper::weightId($data['search_weight'], true);
        }
        $option = Json::encode($data);
        $placeholder = $model->title;
        return $this->show('view', compact('placeholder', 'option'));
    }

    public function renderForm(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        $data['placeholder'] = $model->title;
        return $this->show('config', $data);
    }

    public function parseForm(): array {
        $request = request();
        $data = [];
        foreach($this->defaultData() as $key => $value) {
            $data[$key] = $request->get($key, $value);
        }
        return [
            'title' => $request->get('placeholder'),
            'content' => Json::encode($data),
        ];
    }
    
    private function defaultData(): array {
        return [
            'search_type' => 0,
            'open_history' => 0,
            'search_url' => '',
            'search_suggest_url' => '',
            'search_weight' => 0,
            'search_tag' => '',
            'search_tag_text' => '',
        ];
    }
}