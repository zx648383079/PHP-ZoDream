<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Html\Page */
$this->extend('layout/header');
?>


<div class="container">
    
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">素材管理</h3>
          </div>
          <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>内容</th>
                            <th>类型</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($page->getPage() as $value) :?>
                            <tr>
                                <td><?=$value['id'];?></td>
                                <td><?=$value['type']?></td>
                                <td><?=$value['content']?></td>
                                <td>
                                    <?=Html::a('删除', [null, 'id' => $value['id']])?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="2">
                            <?php $page->pageLink();?>
                        </th>
                    </tr>
                    </tfoot>
                </table>
          </div>
    </div>
    
</div>


<?=$this->extend('layout/footer')?>