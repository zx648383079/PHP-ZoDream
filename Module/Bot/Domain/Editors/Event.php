<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\Model\EditorModel;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class Event implements InputInterface {
    public function form(EditorModel $model) {
        return Theme::text('content', $model->getAttributeSource('content'),
            '参数', '示例：参数');
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

}