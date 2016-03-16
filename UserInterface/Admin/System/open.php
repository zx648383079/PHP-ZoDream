<?php
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
        <h3>编辑器</h3>
        <div class="row">
         	<form class="col-sm-8" action="#" method="GET">
                <div class="input-group">
                    <input type="text" name="file" value="<?php $this->ech('file');?>" class="form-control1 input-search" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div><!-- Input Group -->
            </form>
            </div>
            <div class="row">
         	<form class="col-sm-" action="#" method="GET">
                <div class="input-group">
                    <textarea name="content" class="form-control1" placeholder=""></textarea>
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="submit">保存</button>
                    </span>
                </div>
            </form>
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