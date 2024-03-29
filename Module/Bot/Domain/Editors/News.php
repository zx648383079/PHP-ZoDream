<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\Adapters\ReplyType;
use Module\Bot\Domain\EmulateResponse;
use Module\Bot\Domain\MessageReply;
use Module\Bot\Domain\Model\EditorModel;
use Module\Bot\Domain\Model\MediaModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
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
            <input type="text" class="form-control" placeholder="搜索图文素材" size="20">
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

    public function save(EditorModel $model, Request|array $request) {
        return;
    }

    public function render(EditorModel $reply, MessageReply $response) {
        $items = [];
        $model_list = MediaModel::where('id', $reply->content)
            ->orWhere('parent_id', $reply->content)->orderBy('parent_id', 'asc')->get();
        foreach ($model_list as $item) {
            /** @var $item MediaModel */
            $picUrl = MediaModel::where('type', MediaModel::TYPE_IMAGE)
                ->where('content', $item->thumb)->value('url');
            $items[] = [
                'id' => $item->id,
                'title' => $item->title,
                'thumb' => $item->thumb,
                'thumb_url' => $picUrl
            ];
        }
        return $response->renderData(ReplyType::News, compact('items'));
    }

}