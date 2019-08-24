<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\WeChat\Domain\Model\EditorModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\MediaModel;
/** @var View $this */
/** @var EditorModel $model */
if (!isset($tab_id)) {
    $tab_id = true;
}
if (!is_array($tab_id)) {
    $tab_id = $tab_id ? [0, 1, 2, 3, 7] : [1, 2, 3, 4, 5, 6, 7];
}
if (!isset($model)) {
    $model = new ReplyModel();
}
$type_id = $model->getEditor('type');
$scene = $model->getEditor('scene');
$this->registerJs(sprintf('bindTab(%s, \'%s\');', in_array($type_id, $tab_id) ? $type_id : $tab_id[0], $this->url('./admin/', false)));
?>
<div class="zd-tab wx-editor">
    <div class="zd-tab-head">
        <?php foreach(ReplyModel::$type_list as $key => $item):?>
        <?php if(in_array($key, $tab_id)):?>
        <div class="zd-tab-item" data-id="<?=$key?>">
        <?=$item?>
        </div>
        <?php endif;?>
        <?php endforeach;?>
    </div>
    <div class="zd-tab-body">
        <?php if(in_array(ReplyModel::TYPE_TEXT, $tab_id)):?>
        <div class="zd-tab-item">
            <textarea name="editor[text]"><?=$model->getEditor('text')?></textarea>
        </div>
        <?php endif;?>
        <?php if(in_array(ReplyModel::TYPE_MEDIA, $tab_id)):?>
        <div class="zd-tab-item media-box">
            <div class="row">
                <div class="col-xs-6">
                    <div class="upload-box">
                        <?php if($model->getEditor('media_id')):?>
                        <?=MediaModel::find($model->getEditor('media_id'))->title?>
                        <?php else:?>
                        <img src="/assets/images/upload.png" alt="" >
                        <?php endif;?>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="input-group inline-input-group">
                        <select id="media_type">
                            <?php foreach(MediaModel::$types as $key => $item):?>
                               <option value="<?=$key?>"><?=$item?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="text" placeholder="搜索媒体素材" size="100">
                    </div>
                    <div class="media-grid">
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
            <input type="hidden" name="editor[media_id]" value="<?=$model->getEditor('media_id')?>">
        </div>
        <?php endif;?>
        <?php if(in_array(ReplyModel::TYPE_NEWS, $tab_id)):?>
        <div class="zd-tab-item media-box">
            <div class="row">
                <div class="col-xs-6">
                    <div class="upload-box">
                        <?php if($model->getEditor('news_id')):?>
                        <?=MediaModel::find($model->getEditor('news_id'))->title?>
                        <?php else:?>
                        <img src="/assets/images/upload.png" alt="" >
                        <?php endif;?>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="input-group">
                        <input type="text" placeholder="搜索图文素材" size="100">
                    </div>
                    <div class="media-list">
                        <ul>
                            <li>
                                123123123123
                            </li>
                            <li>
                                123123
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <input type="hidden" name="editor[news_id]" value="<?=$model->getEditor('news_id')?>">
        </div>
        <?php endif;?>
        <?php if(in_array(ReplyModel::TYPE_TEMPLATE, $tab_id)):?>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="template_id">模板ID</label>
                <input type="text" id="template_id" name="editor[template_id]" value="<?=$model->getEditor('template_id')?>" placeholder="示例：模板ID" size="100">
            </div>
            <div class="input-group">
                <label for="template_id">链接</label>
                <input type="text" id="template_url" name="editor[template_url]" value="<?=$model->getEditor('template_url')?>" placeholder="" size="100">
            </div>
            <textarea name="editor[template_data]" placeholder="模板参数：key=value 换行"><?=$model->getEditor('template_data')?></textarea>
            <div class="template-preview"></div>
        </div>
        <?php endif;?>
        <?php if(in_array(ReplyModel::TYPE_EVENT, $tab_id)):?>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="editor_event">参数</label>
                <input type="text" id="editor_event" name="editor[event]" value="<?=$model->getEditor('event')?>" placeholder="示例：参数" size="100">
            </div>
        </div>
        <?php endif;?>
        <?php if(in_array(ReplyModel::TYPE_URL, $tab_id)):?>
        <div class="zd-tab-item form-inline">
            <div class="input-group">
                <label for="editor_url">网址</label>
                <input type="text" id="editor_url" name="editor[url]" value="<?=$model->getEditor('url')?>" placeholder="示例：网址" size="100">
            </div>
        </div>
        <?php endif;?>
        <?php if(in_array(ReplyModel::TYPE_MINI, $tab_id)):?>
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
        <?php endif;?>
        <?php if(in_array(ReplyModel::TYPE_SCENE, $tab_id)):?>
            <div class="zd-tab-item form-inline">
                <div class="input-group">
                    <label for="editor_event">场景</label>
                    <select id="editor_scene" name="editor[scene]">
                        <option value="">请选择</option>
                        <?php foreach(ReplyModel::$scene_list as $key => $item):?>
                        <?php if($key === $scene):?>
                        <option value="<?=$key?>" selected><?=$item?></option>
                        <?php else:?>
                        <option value="<?=$key?>"><?=$item?></option>
                        <?php endif;?>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        <?php endif;?>
    </div>
    <input type="hidden" class="type-input" name="editor[type]" value="<?=$type_id?>">
</div>