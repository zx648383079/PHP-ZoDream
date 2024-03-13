<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\EditorInput;
use Module\Bot\Domain\Model\EditorModel;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class Location implements InputInterface {
    public function form(EditorModel $model) {
        return Theme::select('content', array_merge(['' => '请选择'], EditorInput::$scene_list),
            $model->getAttributeSource('content'), '场景');
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

}