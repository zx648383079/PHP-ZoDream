<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Zodream\Helpers\Json;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Mini implements InputInterface {
    public function form(EditorModel $model) {
        $editor = Json::decode($model->getAttributeSource('content'));
        return <<<HTML
<div class="input-group">
    <label for="module1">APPID</label>
    <input type="text" id="module1" name="editor[appid]" value="{$editor['appid']}" placeholder="小程序APPID" size="100">
</div>
<div class="input-group">
    <label for="module1">路径</label>
    <input type="text" id="module1" name="editor[path]" value="{$editor['path']}" placeholder="小程序页面路径" size="100">
</div>
<div class="input-group">
    <label for="module1">替代网址</label>
    <input type="text" id="module1" name="editor[url]" value="{$editor['url']}" placeholder="老版微信不支持小程序时替代网址" size="100">
</div>
HTML;

    }

    public function save(EditorModel $model, Request|array $request) {
        $content = is_array($request) ? $request['editor'] : $request->get('editor', []);
        $model->content = Json::encode($content);
        return;
    }

    public function render(EditorModel $model, MessageResponse $response) {
        return $response;
    }

    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $editor = Json::decode($model->getAttributeSource('content'));
        $menu->setMini($editor['appid'], $editor['path'], $editor['url']);
    }
}