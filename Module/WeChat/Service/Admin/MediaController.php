<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\TemplateModel;

class MediaController extends Controller {

    protected function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction($type = null) {
        $model_list = MediaModel::when(!empty($type), function ($query) use ($type) {
            $query->where('type', $type);
        })->page();
        return $this->show(compact('model_list', 'type'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = MediaModel::findOrNew($id);
        $template_list = TemplateModel::all();
        return $this->show(compact('model', 'template_list'));
    }
}