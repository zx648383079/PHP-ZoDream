<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    ))
);
$page = $this->get('page');
?>
<div id="page-wrapper">
    <div class="graphs">
        <div class="xs">
        <h3>所有列表</h3>
        <div class="row">
            <div class="mailbox-content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>说明</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($page->getPage() as $value) {?>
                            <tr class="unread checked">
                            <td>
                                <?php echo $value['id'];?>
                            </td>
                            <td>
                                <?php echo $value['name'];?>
                            </td>
                            <td>
                                <?php echo $value['description'];?>
                            </td>
                            <td>
                                <a href="#">编辑</a>
                                <a href="<?php $this->url('classes/delete/id/'.$value['id']);?>">删除</a>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php $page->PageLink();?>
                
             <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="<?php $this->url();?>" method="POST">
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control1" id="focusedinput" placeholder="标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">说明</label>
                            <div class="col-sm-8">
                                <input type="text" name="description" class="form-control1" id="focusedinput" placeholder="说明">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-push-2 col-sm-3">
                                <button type="submit" class="btn-success btn">保存</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="clearfix"> </div>
             </div>
            </div>
       <div class="copy_layout">
         <p>Copyright &copy; 2015.ZoDream All rights reserved.</p>
       </div>
   </div>
      </div>
      <!-- /#page-wrapper -->
   </div>


<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>