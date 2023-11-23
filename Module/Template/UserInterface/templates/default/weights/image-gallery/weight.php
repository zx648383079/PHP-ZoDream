<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Module\Template\Domain\VisualEditor\VisualHelper;
use Module\Template\Domain\VisualEditor\VisualInput;
use Zodream\Helpers\Json;

class ImageGalleryWeight extends BaseWeight {

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
            VisualInput::images('images', '图片', []),
            VisualInput::group('图片设置', [
                VisualInput::text('title', '标题', $model->title),
                VisualInput::select('mode', '平铺方式', [
                    VisualInput::selectOption('同尺寸', 'same'),
                    VisualInput::selectOption('瀑布流', 'same'),
                    VisualInput::selectOption('流', 'same'),
                ], '')->attr('data-tab', 'mode'),
                VisualInput::size('gap', '间隔', ''),
                VisualInput::size('column_count', '几列', ''),
                VisualInput::select('fit', '图片显示', [
                    VisualInput::selectOption('fill'),
                    VisualInput::selectOption('contain'),
                ], ''),
            ]),
            VisualInput::group('图片尺寸', [
                VisualInput::size('width', '宽', ''),
                VisualInput::size('height', '高', ''),
            ])->attr('tab', 'mode-same'),
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
            'images' => '',
            'mode' => '',
            'gap' => '',
            'column_count' => '',
            'fit' => '',
            'width' => '',
            'height' => '',
        ];
    }
}