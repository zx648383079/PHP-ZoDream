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
        <div class="xs">
            <h3>更改密码</h3>
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="<?php $this->url();?>" method="POST">
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">原密码</label>
                            <div class="col-sm-8">
                                <input type="password" name="oldpassword" class="form-control1" id="focusedinput" required placeholder="原密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">新密码</label>
                            <div class="col-sm-8">
                                <input type="password" name="newpassword" required class="form-control1" id="focusedinput" placeholder="新密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-8">
                                <input type="password" name="cpassword" required class="form-control1" id="focusedinput" placeholder="确认密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-push-2 col-sm-3">
                                <button type="submit" class="btn-success btn">保存</button>
                            </div>
                        </div>
                </form>
            </div>
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