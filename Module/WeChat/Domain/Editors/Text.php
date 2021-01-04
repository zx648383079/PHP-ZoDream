<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Text implements InputInterface {
    public function form(EditorModel $model) {
        return Html::tag('textarea', $model->getAttributeSource('content'), [
            'name' => 'content'
        ]);
    }

    public function save(EditorModel $model, Request $request) {
        return;
    }

    public function render(EditorModel $model, MessageResponse $response) {
        return $response->setText($model->content);
    }

    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $menu->setKey('menu_'.$model->id);
    }
}