<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Module\Template\Domain\VisualEditor\VisualHelper;
use Module\Template\Domain\VisualEditor\VisualInput;
use Zodream\Helpers\Json;

class IconBoxWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     * @throws Exception
     */
    public function render(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        $data['title'] = $model->title;
        return $this->show('view', $data);
    }

    public function renderForm(SiteWeightModel $model): array {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        return [
            VisualInput::group('图标', [
                VisualInput::icon('icon', '图标', $data['icon']??''),
                VisualInput::select('icon_position', '图标位置', [
                    VisualInput::selectOption('top'),
                    VisualInput::selectOption('bottom'),
                    VisualInput::selectOption('left'),
                    VisualInput::selectOption('right'),
                ], $data['icon_position'] ?? ''),
                VisualInput::text('icon_url', '链接地址', $data['icon_url']??''),
            ]),
            VisualInput::group('内容', [
                VisualInput::text('title', '标题', $model->title),
                VisualInput::editor('content', '内容', $data['content']),
            ]),
        ];
    }

    public function validateForm(array $input): array {
        $data = [];
        foreach($this->defaultData() as $key => $value) {
            $data[$key] = $input[$key]??$value;
        }
        return [
            'title' => $input['title']??'',
            'content' => Json::encode($data),
        ];
    }
    
    private function defaultData(): array {
        return [
            'icon' => '',
            'icon_position' => '',
            'icon_url' => '',
            'content' => '',
        ];
    }
}