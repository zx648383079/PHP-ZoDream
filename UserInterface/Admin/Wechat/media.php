<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$page = $this->gain('page');
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
                        <?php foreach ($page->getPage() as $value) {?>
                            <tr>
                                <td><?=$value['id'];?></td>
                                <td><?=$value['type']?></td>
                                <td><?=$value['content']?></td>
                                <td>
                                    <a href="<?php $this->url([null, 'id' => $value['id']])?>">删除</a>
                                </td>
                            </tr>
                        <?php }?>
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


<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>