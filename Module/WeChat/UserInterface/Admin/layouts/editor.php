<?php
use Zodream\Template\View;
use Module\WeChat\Domain\Model\EditorModel;
/** @var View $this */
/** @var EditorModel $model */
?>
<div class="zd-tab wx-editor">
    <div class="zd-tab-head">
        <div class="zd-tab-item active">
            文本
        </div>
        <div class="zd-tab-item">
            图片
        </div>
        <div class="zd-tab-item">
            视频
        </div>
        <div class="zd-tab-item">
            图文
        </div>
        <div class="zd-tab-item">
            模板消息
        </div>
        <div class="zd-tab-item">
            事件
        </div>
        <div class="zd-tab-item">
            网址
        </div>
        <div class="zd-tab-item">
            小程序
        </div>
    </div>
    <div class="zd-tab-body">
        <div class="zd-tab-item active">
            <textarea name="editor[text]"><?=$model->getEditor('text')?></textarea>
        </div>
        <div class="zd-tab-item media-box">
            <div class="row">
                <div class="col-xs-6">
                    <div class="upload-box">
                        <img src="/assets/images/upload.png" alt="" >
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="input-group">
                        <input type="text" placeholder="搜索图片" size="100">
                    </div>
                    <div class="media-list">
                        <ul>
                            <li>
                                <img src="/assets/images/upload.png" alt="" >
                            </li>
                            <li>
                                <img src="/assets/images/upload.png" alt="" >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="zd-tab-item">
        </div>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="module1">命名空间</label>
                <input type="text" id="module1" name="module" placeholder="示例：Module\Blog" size="100">
            </div>
        </div>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="template_id">模板ID</label>
                <input type="text" id="template_id" name="editor[template_id]" value="<?=$model->getEditor('template_id')?>" placeholder="示例：模板ID" size="100">
            </div>
            <textarea name="editor[template_data]" placeholder="模板参数：key=value 换行">value="<?=$model->getEditor('template_data')?>"</textarea>
        </div>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="editor_event">参数</label>
                <input type="text" id="editor_url" name="editor[event]" value="<?=$model->getEditor('event')?>" placeholder="示例：参数" size="100">
            </div>
        </div>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="editor_url">网址</label>
                <input type="text" id="editor_url" name="editor[url]" value="<?=$model->getEditor('url')?>" placeholder="示例：网址" size="100">
            </div>
        </div>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="module1">APPID</label>
                <input type="text" id="module1" name="editor[min_appid]" value="<?=$model->getEditor('min_appid')?>" placeholder="小程序APPID" size="100">
            </div>
            <div class="input-group">
                <label for="module1">路径</label>
                <input type="text" id="module1" name="editor[min_path]" value="<?=$model->getEditor('min_path')?>" placeholder="小程序页面路径" size="100">
            </div>
            <div class="input-group">
                <label for="module1">替代网址</label>
                <input type="text" id="module1" name="editor[min_url]" value="<?=$model->getEditor('min_url')?>" placeholder="老版微信不支持小程序时替代网址" size="100">
            </div>
        </div>
    </div>
    <input type="hidden" class="type-input" name="editor[type]" value="0">
</div>