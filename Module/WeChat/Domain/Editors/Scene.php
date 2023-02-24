<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\MessageReply;
use Module\WeChat\Domain\Model\EditorModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Scene\SceneInterface;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Scene implements InputInterface {
    public function form(EditorModel $model) {
        return Theme::select('content', array_merge(['' => '请选择'], EditorInput::$scene_list),
            $model->getAttributeSource('content'), '场景');
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

}