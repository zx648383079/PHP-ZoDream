<?php
namespace Module\Bot\Domain\Editors;

use Module\Bot\Domain\Model\EditorModel;
use Module\Bot\Domain\Model\MediaModel;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Support\Html;

class Media implements InputInterface {
    public function form(EditorModel $model) {
        $mediaId = intval($model->getAttributeSource('content'));
        $preview = $mediaId > 0 ? MediaModel::where('id', $mediaId)->value('title') : '<img src="/assets/images/upload.png" alt="" >';
        $mediaType = Html::select(MediaModel::$types, null, [
            'id' => 'media_type',
            'class' => 'form-control'
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
            <input type="text" class="form-control" placeholder="搜索媒体素材" size="20">
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

    public function save(EditorModel $model, Request|array $request) {
        return;
    }


}