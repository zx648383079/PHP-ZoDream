<?php
use Zodream\Template\View;
/** @var $this View */
?>

<?php if(isset($project_list)):?>
<ul>
    <li><a href="<?=$this->url('./')?>">
            <i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand"><a href="javascript:;">
            <i class="fa fa-money"></i><span>项目列表</span></a>
        <ul>
            <?php foreach($project_list as $item):?>
            <li><a href="<?=$this->url('./money')?>">
                    <i class="fa fa-book"></i><span><?=$item['name']?></span></a></li>
            <?php endforeach;?>
        </ul>
    </li>
</ul>
<?php else?>
<ul>
    <li><a href="<?=$this->url('./')?>">
            <i class="fa fa-arrow-left"></i><span>返回首页</span></a></li>
    <li><a href="<?=$this->url('./project', ['id' => $project->id])?>">
            <i class="fa fa-home"></i><span>项目主页</span></a></li>
    <?php foreach($tree_list as $item):?>
    <li><a href="javascript:;">
            <i class="fa fa-folder-open"></i><span><?=$item['name']?></span></a>
        <ul>
            <?php foreach($item['children'] as $child):?>
            <li><a href="<?=$this->url('./api', ['id' => $child['id']])?>">
                    <i class="fa fa-file"></i><span><?=$child['name']?></span></a></li>
            <?php endforeach;?>
        </ul>
    </li>
    <?php endforeach;?>
   
</ul>
<?php endif;?>
