<?php

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class NavbarWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return string
     * @throws Exception
     */
    public function render(SiteWeightModel $model): string {
        $items = $model->setting('items', []);
        foreach($items as &$item) {
            $item['url'] = $this->converterUrl($item['url']);
        }
        unset($item);
        return $this->show('view', compact('model', 'items'));
    }

    public function renderForm(SiteWeightModel $model): string {
        $weightId = PageWeightModel::where('page_id', $this->pageId())
            ->where('weight_id', '<>', $model->id)->pluck('weight_id');
        $weight_list = empty($weightId) ? [] : SiteWeightModel::whereIn('id', $weightId)->get('id', 'title');
        $page_list = PageModel::where('site_id', $model->site_id)
            ->where('id', '<>', $this->pageId())->get('id', 'title');
        return $this->show('config', compact('model', 'weight_list', 'page_list'));
    }

    public function parseForm(): array {
        $data = parent::parseForm();
        if (!isset($data['settings']) || !isset($data['settings']['items'])) {
            return $data;
        }
        $args = $data['settings']['items'];
        $items = [];
        foreach ($args['title'] as $i => $title) {
            if (empty($title)) {
                continue;
            }
            $icon = $args['icon'][$i];
            $url = [
                'type' => $args['url']['type'][$i]
            ];
            if ($url['type'] === 'target') {
                $url['uri'] = $args['url']['target']['uri'][$i];
                $url['target'] = $args['url']['target']['target'][$i];
            } elseif ($url['type'] === 'url') {
                $url['uri'] = $args['url']['url']['uri'][$i];
                $url['target'] = $args['url']['url']['target'][$i];
            } else {
                $url['id'] = $args['url']['page']['id'][$i];
                $url['target'] = $args['url']['page']['target'][$i];
            }
            $items[] = compact('title', 'icon', 'url');
        }
        $data['settings']['items'] = $items;
        return $data;
    }
}