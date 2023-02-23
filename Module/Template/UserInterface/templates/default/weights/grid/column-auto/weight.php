<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Zodream\Helpers\Str;

class Column4Weight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     */
    public function render(SiteWeightModel $model): string {
        $split_mode = $model->setting('split_mode', true);
        $split_count = $model->setting('split_count', 2);
        $split_items = (array)$model->setting('split_items', []);
        return $this->show('view', compact('split_mode', 'split_count', 'split_items'));
    }

    public function renderForm(SiteWeightModel $model): string {
        $split_mode = $model->setting('split_mode', true);
        $split_count = $model->setting('split_count', 2);
        $split_items = implode(',', (array)$model->setting('split_items', []));
        return $this->show('config', compact('split_mode', 'split_count', 'split_items'));
    }

    public function parseForm(): array {
        $data = parent::parseForm();
        if (!isset($data['settings'])) {
            return $data;
        }
        $data['settings']['split_mode'] = !isset($data['settings']['split_mode']) || Str::toBool($data['settings']['split_mode']);
        $data['settings']['split_count'] = isset($data['settings']['split_count']) ? intval($data['settings']['split_count']) : 2;
        $items = [];
        if (isset($data['settings']['split_items'])) {
            $items = $data['settings']['split_items'];
        }
        if (!is_array($items)) {
            $items = explode(',', (string)$items);
        }
        $data['settings']['split_items'] = array_map('intval', $items);
        return $data;
    }
}