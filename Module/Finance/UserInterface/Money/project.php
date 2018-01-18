<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '理财项目';

$url = $this->url('./money/confirm_earnings');
$js = <<<JS
    function confirm_earnings(id) {
      Dialog.box({
          title: '确定收益',
          url: '{$url}?id='+id
      }).on('done', function() {
        ajaxForm(this.find('form'));
      });
    }
JS;

$this->extend('layouts/header')
    ->registerJs($js);
?>

<div class="search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label class="sr-only" for="exampleInputEmail2">配置项目</label>
            <input type="email" class="form-control" id="exampleInputEmail2" placeholder="配置项目">
        </div>
        <div class="input-group">
            <input class="form-control" type="email" placeholder="Enter email">
        </div>
        <div class="input-group">
            <label class="sr-only" for="exampleInputPassword2">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
</div>

    <table>
        <thead>
        <tr>
        <th>ID</th>
        <th>配置项目</th>
        <th>资金</th>
        <th>形态</th>
<!--        <th>占比</th>-->
        <th>(预估)收益率</th>
        <th>状态</th>
        <th>起息日期</th>
        <th>到期日期</th>
        <th>操作</th>
    </tr>
        </thead>
        <tbody>
            <?php foreach ($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->money?></td>
                <td><?=$item->product->name?></td>
<!--                <td>--><!--%</td>-->
                <td><?=$item->earnings?></td>
                <td>
                    <?php if ($item->status == 1):?>
                    <button type="button" class="btn btn-success btn-xs">进行中</button>
                    <?php else:?>
                    <button type="button" class="btn btn-danger btn-xs">已结束</button>                    
                    <?php endif;?>
                </td>
                <td><?=$item->start_at?></td>
                <td><?=$item->end_at?></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./money/edit_project', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-success btn-xs" href="javascript:void(0);" onclick="confirm_earnings(<?=$item->id?>)">确认收益</a>
                        <a class="btn btn-danger" data-type="post" href="<?=$this->url('./money/del_project', ['id' => $item->id])?>">删除</a>
                     </div>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>

    <hr/>

    <a class="btn btn-success" href="<?=$this->url('./money/add_project')?>">新增项目</a>

<?php
$this->extend('layouts/footer');
?>