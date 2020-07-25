<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Infrastructure\Http\Request;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

interface InputInterface {
    public function form(EditorModel $model);

    public function save(EditorModel $model, Request $request);

    /**
     * @param EditorModel $model
     * @param MessageResponse $response
     * @return EditorModel|MessageResponse
     */
    public function render(EditorModel $model, MessageResponse $response);

    public function renderMenu(EditorModel $model, MenuItem $menu);
}