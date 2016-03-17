<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    ))
);
?>

<div id="page-wrapper">
    <div class="graphs">
        <div class="widget_head">文件系统     <a href="<?php $this->url(null, 'dir='.$this->get('forword'));?>">返回</a>  <a href="<?php $this->url('system/open', 'file=/new.txt');?>">新建</a></div>
        <?php foreach ($this->get('dirs', array()) as $value) {?>
        	<a href="<?php $this->url(null, 'dir='. $value['full']);?>">
             <div class="col-sm-3 widget_1_box">
                <div class="wid-social sky">
                    <div class="social-icon text-center">
                        <i class="glyphicon glyphicon-folder-close"></i>
                    </div>
                    <div class="social-info">
                        <h4 class="counttype text-center"> <?php echo $value['name'];?></h4>
                    </div>
                </div>
			  </div>
             </a>
        <?php }?>
		 	 <?php foreach ($this->get('files', array()) as $value) {?>
             <a href="<?php $this->url('system/open', 'file='. $value['full']);?>">
             <div class="col-sm-3 widget_1_box">
                <div class="wid-social sky">
                    <div class="social-icon text-center">
                        <i class="glyphicon glyphicon-file"></i>
                    </div>
                    <div class="social-info">
                        <h4 class="counttype text-center"> <?php echo $value['name'];?></h4>
                    </div>
                </div>
			  </div>
             </a>
              <?php }?>
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
    ))
);
?>