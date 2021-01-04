<?php
namespace Module\WeChat\Domain\Editors;

use Module\WeChat\Domain\Model\EditorModel;
use Module\WeChat\Domain\Model\MediaModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class Media implements InputInterface {
    public function form(EditorModel $model) {
        $mediaId = intval($model->getAttributeSource('content'));
        $preview = $mediaId > 0 ? MediaModel::where('id', $mediaId)->value('title') : '<img src="/assets/images/upload.png" alt="" >';
        $mediaType = Html::select(MediaModel::$types, null, [
            'id' => 'media_type'
        ]);
        return <<<HTML
<div class="row media-box">
    <div class="col-xs-6">
        <div class="upload-box">
            {$preview}
        </div>
    </div>
    <div class="col-xs-6">
        <div class="input-group inline-input-group">
            {$mediaType}
            <input type="text" placeholder="搜索媒体素材" size="20">
        </div>
        <div class="media-grid">
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
        $model = MediaModel::find($reply->content);
        if (!$model->media_id) {
            return $response->setText('内容有误');
        }
        if ($model->type === MediaModel::TYPE_IMAGE) {
            return $response->setImage($model->media_id);
        }
        if ($model->type === MediaModel::TYPE_VIDEO) {
            return $response->setVideo($model->media_id, $model->title);
        }
        if ($model->type === MediaModel::TYPE_VOICE) {
            return $response->setVoice($model->media_id);
        }
        return $response->setText('内容有误');
    }

    public function renderMenu(EditorModel $model, MenuItem $menu) {
        $menu->setKey('menu_'.$model->id);
    }
}