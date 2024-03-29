<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '项目:'.$project->name;
$js = <<<JS
bindProject({$project->id});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="version-bar">
            版本
    <select id="version">
        <?php foreach($version_list as $item):?>
        <option value="<?=$item['id']?>" <?=$version == $item['id'] ? 'selected' : '' ?>><?=$item['name']?></option>
        <?php endforeach;?>
    </select>
    <button class="btn">创建新版本</button>
</div>
<div class="page-header">
    <h3>项目主页</h3>

    <div class="btn-group opt-btn">
        <a class="btn btn-default" href="<?=$this->url('./@admin/project/edit', ['id' => $project->id])?>">编辑</a>
        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/project/delete', ['id' => $project->id])?>">删除</a>
    </div>
</div>

<div class="panel-body">
    <p class="text-muted"><label>项目名称：</label><?=$project->name?></p>
    <p class="text-muted"><label>创建时间：</label><?=$project->created_at?></p>
    <p class="text-muted"><label>更新时间：</label><?=$project->updated_at?></p>

    <p class="text-muted"><label>项目描述：</label><span style="word-break:break-all"><?=$project->description?></span></p>
    <?php if($project->type == 1):?>
    <p class="text-muted"><label>环境域名：</label>
    </p>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>环境标识符</th>
                <th>标识符备注</th>
                <th>环境域名</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($project->environment as $item):?>
                <tr>
                    <td>
                        <?=$item['name']?>
                    </td>
                    <td>
                        <?=$item['title']?>
                    </td>
                    <td>
                        <code><?=$item['domain']?></code>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>

    </div>
    <?php endif;?>
</div>