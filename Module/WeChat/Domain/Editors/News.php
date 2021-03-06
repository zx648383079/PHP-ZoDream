<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Module\WeChat\Domain\Model\MediaModel;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class News implements InputInterface {
    public function form(EditorModel $model) {
        $mediaId = intval($model->getAttributeSource('content'));
        $preview = $mediaId > 0 ? MediaModel::where('id', $mediaId)->value('title') : '<img src="/assets/images/upload.png" alt="" >';
        return <<<HTML
<div class="row media-box">
    <div class="col-xs-6">
        <div class="upload-box">
            {$preview}
        </div>
    </div>
    <div class="col-xs-6">
        <div class="input-group">
            <input type="text" placeholder="搜索图文素材" size="20">
        </div>
        <div class="media-list">
            <ul>
            </ul>
        </div>
    </div>
</div>
<input type="hidden" name="content" value="{$mediaId}">
HTML;
    }

    public function save(EditorModel $model, Request $request) {
        return;
    }

    public function render(EditorModel $reply, MessageResponse $response) {
        $model_list = MediaModel::where('id', $reply->content)
            ->orWhere('parent_id', $reply->content)->orderBy('parent_id', 'asc')->get();
        foreach ($model_list as $item) {
            /** @var $item MediaModel */

            $picUrl = empty($this->message) ? $item->thumb : MediaModel::where('type', MediaModel::TYPE_IMAGE)
                ->where('content', $item->thumb)->value('url');
            $response->addNews($item->title, $item->title,
                $picUrl
            );
        }
        return $response;
    }

    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $menu->setKey('menu_'.$model->id);
    }
}