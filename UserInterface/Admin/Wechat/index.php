<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Template\View */
/** @var $models \Domain\Model\WeChat\WechatModel[] */
$this->title = '微信公众号管理平台';
$this->extend('layout/header');
?>


<div class="container">
    
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">公众号管理
                    <a href="<?=Url::to('wechat/add')?>" class="btn btn-default">添加</a></h3>
          </div>
          <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $item) :?>
                            <tr>
                                <td><?=$item['id'];?></td>
                                <td><?=$item['name'];?></td>
                                <td>
                                    [<a href="<?=Url::to('wechat/change/id/'.$item['id']);?>">管理</a>]
                                    [<a href="<?=Url::to('wechat/add/id/'.$item['id']);?>">编辑</a>]
                                    [<a href="<?=Url::to(['wechat/delete', 'id' => $item['id']])?>" data-confirm="确认删除？">删除</a>]</td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
          </div>
    </div>
    
</div>


<?=$this->extend('layout/footer')?>