<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>


<div class="container">
    
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">公众号管理 <a href="wechat/add" class="btn btn-default">添加</a></h3>
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
                        <?php foreach ($this->get('data', array()) as $value) {?>
                            <tr>
                                <td><?php echo $value['id'];?></td>
                                <td><?php echo $value['name'];?></td>
                                <td><?php echo $value['email'];?></td>
                                <td><?php echo $value['login_num'];?></td>
                                <td><?php echo $value['update_ip'];?></td>
                                <td><?php $this->time($value['update_at']);?></td>
                                <td>[<a href="<?php $this->url('user/addUser/id/'.$value['id']);?>">编辑</a>][删除]</td>
                            </tr>
                        <?php }?>
                    </tbody>
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