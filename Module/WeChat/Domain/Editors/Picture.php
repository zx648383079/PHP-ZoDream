<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\EditorModel;
use Module\WeChat\Domain\Scene\SceneInterface;
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

    public function render(EditorModel $model, MessageResponse $response) {
        $name = $model->content;
        /** @var SceneInterface $instance */
        $instance = new $name();
        return $instance->enter();
    }


    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $menu->setType(MenuItem::SYSTEM_PHOTO_ALBUM);
    }
}