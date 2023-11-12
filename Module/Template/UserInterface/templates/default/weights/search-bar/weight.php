<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Zodream\Helpers\Json;
use Module\Template\Domain\VisualEditor\VisualHelper;
use Module\Template\Domain\VisualEditor\VisualInput;

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

    public function renderForm(SiteWeightModel $model): array {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        return [
            VisualInput::text('placeholder', '搜索提示', $model->title),
            VisualInput::switch('open_history', '开启历史记录', $data['open_history']??0),
            VisualInput::select('search_type', '搜索类型', [
                ['name' => '网址', 'value' => 0],
                ['name' => '触发部件', 'value' => 1],
                ['name' => '页面内搜索', 'value' => 2],
            ], $data['search_type']??0)->attr('data-tab', 'search-tab'),
            VisualInput::group([
                VisualInput::text('search_url', '搜索跳转地址', $data['search_url']??''),
                VisualInput::text('search_suggest_url', '搜索建议地址', $data['search_suggest_url']??''),
            ])->class('search-tab-0'),
            VisualInput::group([
                VisualInput::select('search_weight', '部件', [], $data['search_weight']??''),
            ])->class('search-tab-1'),
            VisualInput::group([
                VisualInput::text('search_tag', '搜索标签', $data['search_tag']??''),
                VisualInput::text('search_tag_match', '搜索标签内匹配内容', $data['search_tag_match']??''),
            ])->class('search-tab-2'),
        ];
    }

    public function validateForm(array $input): array {
        $data = [];
        foreach($this->defaultData() as $key => $value) {
            $data[$key] = $input[$key]??$value;
        }
        return [
            'title' => $input['placeholder']??'',
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