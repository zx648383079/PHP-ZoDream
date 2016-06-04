<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>


<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">友情链接 <a href="<?php $this->url('link/add');?>" class="btn btn-primary">新增</a>  </div>
        <div class="panel-body">
        </div>

        <!-- Table -->
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th> 
                <th>网址</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($this->get('data', array()) as $value) {?>
                    <tr>
                        <td><?php echo $value['id'];?></td>
                        <td><?php echo $value['name'];?></td>
                        <td><?php echo $value['url'];?></td>
                        <td>
                            <a href="<?php $this->url('link/add/id/'.$value['id']);?>" class="btn btn-primary">编辑</a>   
                            
                            <a href="<?php $this->url('link/delete/id/'.$value['id']);?>" class="btn btn-danger">删除</a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>