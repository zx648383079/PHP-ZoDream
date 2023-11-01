<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;

class Text implements InputInterface {
    public function form(EditorModel $model) {
        return Html::tag('textarea', $model->getAttributeSource('content'), [
            'name' => 'content',
            'class' => 'form-control'
        ]);
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

}