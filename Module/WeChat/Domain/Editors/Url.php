<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Support\Html;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Url implements InputInterface {
    public function form(EditorModel $model) {
        return Theme::text('content', $model->getAttributeSource('content'),
            '网址', '示例：网址');
    }

    public function save(EditorModel $model, Request $request) {
        return;
    }

    public function render(EditorModel $model, MessageResponse $response) {
        return $response->setText($model->content);
    }

    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $menu->setUrl($model->getAttributeSource('content'));
    }
}