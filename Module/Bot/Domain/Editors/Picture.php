<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\EditorInput;
use Module\Bot\Domain\MessageReply;
use Module\Bot\Domain\Model\EditorModel;
use Module\Bot\Domain\Scene\SceneInterface;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Picture implements InputInterface {
    public function form(EditorModel $model) {
        return Theme::select('content', array_merge(['' => '请选择'], EditorInput::$scene_list),
            $model->getAttributeSource('content'), '场景');
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

}