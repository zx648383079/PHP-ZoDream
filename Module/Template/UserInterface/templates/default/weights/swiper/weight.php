<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Module\Template\Domain\VisualEditor\VisualHelper;
use Module\Template\Domain\VisualEditor\VisualInput;
use Zodream\Helpers\Json;

class SwiperWeight extends BaseWeight {

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
                VisualInput::select('mode', '显示方式', [
                    VisualInput::selectOption('单张', 'single'),
                    VisualInput::selectOption('按数量', 'count'),
                    VisualInput::selectOption('按尺寸', 'size'),
                ], '')->attr('data-tab', 'mode'),
                VisualInput::select('fit', '图片显示', [
                    VisualInput::selectOption('fill'),
                    VisualInput::selectOption('contain'),
                ], ''),
                VisualInput::switch('autoplay', '自动轮播', ''),
                VisualInput::switch('over_pause', '悬停', ''),
                VisualInput::number('space', '停留时长', ''),
                VisualInput::number('speed', '动画时长', ''),
            ]),
            VisualInput::group('图片尺寸', [
                VisualInput::size('width', '宽', ''),
                VisualInput::size('height', '高', ''),
                VisualInput::size('gap', '间隔', ''),
            ])->attr('tab', 'mode-size'),
            VisualInput::group('图片数量', [
                VisualInput::size('gap', '间隔', ''),
                VisualInput::size('column_count', '显示几个', ''),
            ])->attr('tab', 'mode-count')
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