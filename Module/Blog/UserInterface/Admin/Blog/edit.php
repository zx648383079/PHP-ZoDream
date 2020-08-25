<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
use Module\Blog\Domain\Weather;
use Module\Blog\Domain\CCLicenses;

/** @var $this View */
$lang_list = ['Html', 'Css', 'Sass', 'Less', 'TypeScript', 'JavaScript', 'PHP', 'Go', 'C#', 'ASP.NET', '.NET Core', 'Python', 'C', 'C++', 'Java', 'Kotlin', 'Swift', 'Objective-C', 'Dart', 'Flutter'];
$weather_list = Weather::getList();
$this->title = ($model->id > 0 ? '编辑' : '新增'). '文章';
$configs = app('request')->isMobile() ?
    '{toolbars: [[\'fullscreen\', \'source\', \'undo\', \'redo\', \'bold\', \'italic\', \'underline\', \'customstyle\', \'link\',\'simpleupload\', \'insertvideo\']],}' : '{}';
$js = <<<JS
bindEdit({$configs});
JS;

$this->registerJs($js);
?>

<div class="page-title">
    <h1><?=$this->title?></h1>
    <?php if($model->id > 0 || $model->parent_id > 0):?>
    <div class="language-toggle">
    切换到
    <?php if($model->parent_id < 1):?>
     <a href="<?=$this->url('./@admin/blog/edit', ['id' => $model->id, 'language' => 'en'])?>">EN</a>
     <?php else:?>
     <a href="<?=$this->url('./@admin/blog/edit', ['id' => $model->parent_id])?>">中</a>
    <?php endif;?>
    </div>
    <?php endif;?>
</div>
<?=Form::open($model, './@admin/blog/save')?>
    <div class="zd-tab">
        <div class="zd-tab-head">
            <div class="zd-tab-item active">
                基本
            </div><div class="zd-tab-item">
                详情
            </div>
        </div>
        <div class="zd-tab-body">
            <div class="zd-tab-item active">
                <?=Form::text('title', true)?>
                <?php if($model->parent_id < 1):?>
                <?=Form::select('term_id', [$term_list], true)?>
                <?=Form::select('programming_language', array_merge(['' => '请选择'], array_combine($lang_list, $lang_list)))?>
                <?=Form::select('type', ['原创', '转载'])?>
                <div class="if_type_1">
                    <?=Theme::text('meta[source_url]', $metaItems['source_url'], '原文链接')?>
                    <?=Theme::text('meta[source_author]', $metaItems['source_author'], '原文作者')?>
                    <?=Theme::switch('meta[is_hide]', $metaItems['is_hide'], '是否隐藏')?>
                </div>
                <?=Form::file('thumb')?>
                <?=Form::select('open_type', ['公开', '仅登录', 5 => '密码', 6 => '购买', 2 => '草稿'])?>
                <?=Form::text('open_rule')?>
                <?php endif;?>
                <?=Form::select('edit_type', ['Ueditor', 'MarkDown'])?>
                <?=Theme::select('meta[weather]', array_merge(['' => '请选择'], $weather_list), $metaItems['weather'], '天气')?>
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
                <?=Theme::file('meta[audio_url]', $metaItems['audio_url'], '音频')->allow('audio/*')?>
                <?=Theme::file('meta[video_url]', $metaItems['video_url'], '视频')->isFile('video/*')?>
                <?=Theme::select('meta[cc_license]', [CCLicenses::getList(), 'label', 'name', ['' => '请选择']], $metaItems['cc_license'], '版权协议')?>
                <?=Theme::switch('meta[comment_status]', $metaItems['comment_status'], '评论状态')?>
                <?php if($model->parent_id < 1):?>
                <div class="input-group">
                    <label>标签</label>
                    <div>
                        <select name="tag[]" id="tag-box" multiple style="width: 100%">
                            <?php foreach($tags as $item):?>
                            <option value="<?=$item['id']?>" selected><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <div class="zd-tab-item">
                <textarea id="editor-container" style="height: 400px;" name="content" required><?=$model->content?></textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="language" value="<?=$model->language ?: 'zh'?>">
    <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
<?= Form::close('id') ?>