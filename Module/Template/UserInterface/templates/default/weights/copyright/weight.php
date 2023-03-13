<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Zodream\Html\Dark\Theme;

class CopyrightWeight extends BaseWeight {

    /**
     * 获取生成的部件视图
     * @param SiteWeightModel $model
     * @return mixed
     * @throws Exception
     */
    public function render(SiteWeightModel $model): string{
        $args = explode("\n", $model->content);
        $company = $model->title;
        $icp_beian = $args[0]??'';
        $is_beian = $args[1]??'';
        $is_beian_code = '';
        if (!empty($is_beian) && preg_match('/\d+/', $is_beian, $match)) {
            $is_beian_code = $match[0];
        }
        return $this->show('view', compact('company', 'icp_beian', 'is_beian', 'is_beian_code'));
    }

    public function renderForm(SiteWeightModel $model): string
    {
        $args = explode("\n", $model->content);
        return Theme::text('company', $model->title, '版权人').
            Theme::text('icp_beian', $args[0]??'', 'ICP备案号').
            Theme::text('is_beian', $args[1]??'', '网安备案号')
            ;
    }

    public function parseForm(): array {
        $request = request();
        return [
            'title' => $request->get('company'),
            'content' => sprintf("%s\n%s", $request->get('icp_beian'), $request->get('is_beian')),
        ];
    }
}