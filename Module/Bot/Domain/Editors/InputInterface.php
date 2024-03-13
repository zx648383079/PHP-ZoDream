<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\Model\EditorModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

interface InputInterface {
    public function form(EditorModel $model);

    public function save(EditorModel $model, Request|array $request);

}