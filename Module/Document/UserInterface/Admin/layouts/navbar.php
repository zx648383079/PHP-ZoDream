<?php
use Zodream\Template\View;
/** @var $this View */
?>

<?php if(isset($project_list)):?>
<ul>
    <li><a href="<?=$this->url('./admin')?>">
            <i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-money"></i><span>项目列表</span></a>
        <ul>
            <?php foreach($project_list as $item):?>
            <li><a href="<?=$this->url('./admin/project', ['id' => $item['id']])?>">
                    <i class="fa fa-book"></i><span><?=$item['name']?></span></a></li>
            <?php endforeach;?>
            <li><a href="<?=$this->url('./admin/project/create')?>">
                    <i class="fa fa-plus"></i><span>新建项目</span></a></li>
        </ul>
    </li>
</ul>
<?php else:?>
<ul>
    <li><a href="<?=$this->url('./admin')?>">
            <i class="fa fa-arrow-left"></i><span>返回首页</span></a></li>
    <li><a href="<?=$this->url('./admin/project', ['id' => $project->id])?>">
            <i class="fa fa-home"></i><span>项目主页</span></a></li>
    <?php foreach($tree_list as $item):?>
    <li><a href="javascript:;">
            <i class="fa fa-folder-open"></i><span><?=$item['name']?></span></a>
        <ul>
            <?php foreach($item['children'] as $child):?>
            <li><a href="<?=$this->url('./admin/api', ['id' => $child['id']])?>">
                    <i class="fa fa-file"></i><span><?=$child['name']?></span></a></li>
            <?php endforeach;?>
            <li><a href="<?=$this->url('./admin/api/create', ['project_id' => $project->id, 'parent_id' => $item['id']])?>">
                    <i class="fa fa-plus"></i><span>新建接口</span></a></li>
        </ul>
    </li>
    <?php endforeach;?>
    <li><a href="<?=$this->url('./admin/api/create', ['project_id' => $project->id])?>">
                    <i class="fa fa-plus"></i><span>新建接口</span></a></li>
</ul>
<?php endif;?>