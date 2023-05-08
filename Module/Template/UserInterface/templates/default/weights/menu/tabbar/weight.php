<?php

use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Model\SitePageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Zodream\Helpers\Json;
use Module\Template\Domain\VisualEditor\VisualHelper;

class TabbarWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     */
    public function render(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        $data['nav_items'] = VisualHelper::formatUrlTree($data['nav_items']);
        return $this->show('view', $data);
    }

    public function renderForm(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        $weightId = SitePageWeightModel::where('page_id', $this->pageId())
            ->where('weight_id', '<>', $model->id)->pluck('weight_id');
        $data['weight_list'] = empty($weightId) ? [] : SiteWeightModel::whereIn('id', $weightId)->get('id', 'title');
        $data['page_list'] = SitePageModel::where('site_id', $model->site_id)
            ->where('id', '<>', $this->pageId())->get('id', 'title');
        return $this->show('config', $data);
    }


    public function parseForm(): array {
        $request = request();
        $data = [];
        foreach($this->defaultData() as $key => $def) {
            $value = $request->get($key, $def);
            $data[$key] = is_array($def) ? VisualHelper::formatUrlForm($value) : $value;
        }
        return [
            'content' => Json::encode($data),
        ];
    }
    
    private function defaultData(): array {
        return [
            'un_selected_color' => '',
            'selected_color' => '',
            'nav_items' => [],
        ];
    }
}