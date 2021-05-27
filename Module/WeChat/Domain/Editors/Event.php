<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Event implements InputInterface {
    public function form(EditorModel $model) {
        return Theme::text('content', $model->getAttributeSource('content'),
            '参数', '示例：参数');
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

    public function render(EditorModel $model, MessageResponse $response) {
        return $response->setText($model->content);
    }


    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $menu->setKey('menu_'.$model->id);
    }
}