<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head',
                'navbar'
		)), array(
            '@admin/css' => array(
                'custom.css'
            )
        )
);
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
                            <th>页面</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        	$isFirst = true;
                        	foreach ($this->get('singles', array()) as $value) {?>
                            <tr class="unread checked">
                            <td>
                                <?php echo $value['id'];?>
                            </td>
                            <td>
                                <?php echo $value['name'];?>
                            </td>
                            <td>
                                <a href="<?php $this->url('singles/view/id/'.$value['id']);?>">查看</a> 
                                <a href="<?php $this->url('singles/edit/id/'.$value['id']);?>">编辑</a> 
                                <?php if ($value['name'] == 'index' && !$isFirst) {?><a href="<?php $this->url('singles/delete/id/'.$value['id']);?>">删除</a><?php }?>
                            </td>
                        </tr>
                        <?php 
                        		if ($isFirst && $value['name'] == 'index') {
                        			$isFirst = false;
                        		}
                        	}?>
                    </tbody>
                </table>
               </div>
            </div>
            <div class="clearfix"> </div>
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
		)), array(
            '@admin/js' => array(
                'metisMenu.min',
                'custom'
            )
        )
);
?>