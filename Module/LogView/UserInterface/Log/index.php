<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Bootstrap\TableWidget;
use Module\LogView\Domain\Tag;
/** @var $this View */
$this->title = 'ZoDream Log Viewer';
$columns = [
    'id' => 'Id',
    'date' => '日期',
    'time' => '时间',
    'cs_method' => '方法',
    'sc_status' => '协议状态',
    'c_ip' => '客户端IP',
    'cs_uri_stem' => 'URL资源',
    'cs_uri_query' => 'URL查询',
    
    'cs_referer' => '引用网站',
    'time_taken' => '所用时间',
];
$operators = [
    '=', '<', '>', '<=', '>=', '<>', '!=',
    'in', 'not in', 'is', 'is not',
    'like', 'like binary', 'not like', 'between', 'not between', 'ilike',
    '&', '|', '^', '<<', '>>',
    'rlike', 'regexp', 'not regexp',
    '~', '~*', '!~', '!~*', 'similar to',
    'not similar to'
];
?>

<div class="search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <select name="name">
            <?php foreach($columns as $key => $item):?>
               <option value="<?=$key?>"><?=$item?></option>
            <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <select name="operator">
            <?php foreach($operators as $item):?>
               <option value="<?=$item?>"><?=$item?></option>
            <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <input type="hidden" name="id" value="<?=$file->id?>">
    </form>
</div>

<div class="over-table">
    <table class="table table-hover tag-table">
        <thead>
            <tr>
                <?php foreach($columns as $item):?>
                <th><?=$item?></th>
                <?php endforeach;?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($log_list as $log):?>
               <tr class="<?=Tag::has($log) ? 'danger' : ''?>">
                    <?php foreach($columns as $key => $item):?>
                    <td>
                        <span data-url="<?=$this->url('./log/tag', ['name' => $key])?>"><?=$log[$key]?></span>
                        <i class="fa fa-tag"></i>
                    </td>
                    <?php endforeach;?>  
               </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?=$log_list->getLink()?>
