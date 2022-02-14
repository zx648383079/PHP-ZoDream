<?php
declare(strict_types=1);
namespace Module\SMS\Service\Api\Admin;

use Domain\Model\SearchModel;
use Module\SMS\Domain\Model\SmsTemplateModel;

class TemplateController extends Controller {

    public function indexAction(int $type = 0, string $keywords = '') {
        $model_list = SmsTemplateModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type);
            })->page();
        return $this->renderPage($model_list);
    }

    public function detailAction(int $id) {
        $model = SmsTemplateModel::find($id);
        if (empty($model)) {
            return $this->renderFailure('短信不存在');
        }
        return $this->render($model);
    }

    public function saveAction() {
        $model = new SmsTemplateModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        SmsTemplateModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}