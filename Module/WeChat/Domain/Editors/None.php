<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class None implements InputInterface {
    public function form(EditorModel $model) {
        return '';
    }

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

    public function render(EditorModel $model, MessageResponse $response) {
        return $response;
    }


    public function renderMenu(EditorModel $model, MenuItem $menu) {
    }
}