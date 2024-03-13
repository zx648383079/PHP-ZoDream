<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\Model\EditorModel;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class Url implements InputInterface {
    public function form(EditorModel $model) {
        return Theme::text('content', $model->getAttributeSource('content'),
            '网址', '示例：网址');
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

}