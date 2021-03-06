<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Helpers\Json;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Template implements InputInterface {
    public function form(EditorModel $model) {
        $editor = Json::decode($model->getAttributeSource('content'));
        return <<<HTML
<div class="input-group">
    <label for="template_id">模板ID</label>
    <input type="text" id="template_id" name="editor[template_id]" value="{$editor['template_id']}" placeholder="示例：模板ID" size="100">
</div>
<div class="input-group">
    <label for="template_id">链接</label>
    <input type="text" id="template_url" name="editor[template_url]" value="{$editor['template_url']}" placeholder="" size="100">
</div>
<textarea name="editor[template_data]" placeholder="模板参数：key=value 换行">{$editor['template_data']}</textarea>
<div class="template-preview"></div>
HTML;
    }

    public function save(EditorModel $model, Request $request) {
        $content = $request->get('editor', []);
        $model->content = Json::encode($content);
        return;
    }

    public function render(EditorModel $model, MessageResponse $response) {
        return $response->setText($model->content);
    }

    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $menu->setKey('menu_'.$model->id);
    }
}