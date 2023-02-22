<?php

use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;

class NavbarWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param PageWeightModel $model
     * @return string
     * @throws Exception
     */
    public function render(PageWeightModel $model){
        $items = $model->setting('items', []);
        foreach($items as &$item) {
            $item['url'] = $this->converterUrl($item['url']);
        }
        unset($item);
        return $this->show('view', compact('model', 'items'));
    }

    public function renderConfig(PageWeightModel $model) {
        $weight_list = PageWeightModel::where('page_id', $model->page_id)
            ->where('id', '<>', $model->id)->get('id', 'title');
        $page_list = PageModel::where('site_id', $model->site_id)
            ->where('id', '<>', $model->page_id)->get('id', 'title');
        return $this->show('config', compact('model', 'weight_list', 'page_list'));
    }

    public function parseConfigs() {
        $data = parent::parseConfigs();
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