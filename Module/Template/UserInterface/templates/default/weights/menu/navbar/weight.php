<?php

use Module\Template\Domain\Model\SitePageModel;
use Module\Template\Domain\Model\SitePageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Zodream\Helpers\Json;
use Module\Template\Domain\VisualEditor\VisualHelper;

class NavbarWeight extends BaseWeight {

    public function render(SiteWeightModel $model): string {
        $data = VisualHelper::formatFormData($model->content, $this->defaultData());
        $data['title'] = $model->title;
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
        $data['nav_items'] = htmlspecialchars(Json::encode($data['nav_items']));
        return $this->show('config', $data);
    }

    public function parseForm(): array {
        $request = request();
        $data = [];
        foreach($this->defaultData() as $key => $def) {
            $value = $request->get($key, $def);
            $data[$key] = is_array($def) && is_string($value) ? Json::decode($value) : $value;
        }
        return [
            'title' => $request->get('title'),
            'content' => Json::encode($data),
        ];
    }

    private function formatData($content) {
        $items = empty($content) ? [] : Json::decode($content);
        $data = [];
        foreach($this->defaultData() as $key => $value) {
            if (!isset($items[$key])) {
                $data[$key] = $value;
                continue;
            }
            if (is_int($value)) {
                $data[$key] = intval($items[$key]);
            } else {
                $data[$key] = $items[$key];
            }
        }
        return $data;
    }
    
    private function defaultData(): array {
        return [
            'un_selected_color' => '',
            'selected_color' => '',
            'nav_items' => [],
        ];
    }
}