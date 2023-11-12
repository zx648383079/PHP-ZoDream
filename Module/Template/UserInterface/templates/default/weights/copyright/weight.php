<?php

use Module\Template\Domain\Model\SiteWeightModel;
use Module\Template\Domain\VisualEditor\BaseWeight;
use Module\Template\Domain\VisualEditor\VisualInput;
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

    public function renderForm(SiteWeightModel $model): array
    {
        $args = explode("\n", $model->content);
        return [
            VisualInput::text('company', '版权人', $model->title, ),
            VisualInput::text('icp_beian', 'ICP备案号', $args[0]??'', ).
            VisualInput::text('is_beian', '网安备案号', $args[1]??'')
        ];
    }

    public function validateForm(array $input): array {
        return [
            'title' => $input['company']??'',
            'content' => sprintf("%s\n%s", $input['icp_beian']??'', $input['is_beian']??''),
        ];
    }
}