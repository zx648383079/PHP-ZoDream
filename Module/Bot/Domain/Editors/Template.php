<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\Adapters\ReplyType;
use Module\Bot\Domain\EmulateResponse;
use Module\Bot\Domain\MessageReply;
use Module\Bot\Domain\Model\EditorModel;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Template implements InputInterface {
    public function form(EditorModel $model) {
        $editor = Json::decode($model->getAttributeSource('content'));
        return <<<HTML
<div class="input-group">
    <label for="template_id">模板ID</label>
    <input type="text" id="template_id" class="form-control" name="editor[template_id]" value="{$editor['template_id']}" placeholder="示例：模板ID" size="100">
</div>
<div class="input-group">
    <label for="template_id">链接</label>
    <input type="text" id="template_url" class="form-control" name="editor[template_url]" value="{$editor['template_url']}" placeholder="" size="100">
</div>
<textarea name="editor[template_data]" class="form-control" placeholder="模板参数：key=value 换行">{$editor['template_data']}</textarea>
<div class="template-preview"></div>
HTML;
    }

    public function save(EditorModel $model, Request|array $request) {
        $content = is_array($request) ? $request['editor'] : $request->get('editor', []);
        $model->content = Json::encode($content);
        return;
    }

    public function render(EditorModel $model, MessageReply $response) {
        $editor = Json::decode($model->getAttributeSource('content'));
        // $editor['template_id'], $editor['template_data'], $editor['template_url']
        return $response->renderData(ReplyType::Template, $editor);
    }

}