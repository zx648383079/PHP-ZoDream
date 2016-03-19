<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>
<body id="login">
  <div class="login-logo">
    <a href="<?php $this->url('/');?>"><img src="<?php $this->asset('images/logo.png');?>" alt=""/></a>
  </div>
  <h2 class="form-heading">注册</h2>
  <form class="form-signin app-cam" action="<?php $this->url();?>" method="POST">
      <input type="text" class="form-control1" name="name" placeholder="用户名" required autofocus="">
      <input type="email" class="form-control1" name="email" placeholder="邮箱" required autofocus="">
      <input type="password" class="form-control1" name="password" placeholder="密码" required>
      <input type="password" class="form-control1" name="cpassword" placeholder="确认密码" required>
      <label class="checkbox-custom check-success">
          <input type="checkbox" required name="agree" value="agree" id="checkbox1"> <label for="checkbox1">我同意遵守<a href="javascript:void(0);" data-toggle="modal" data-target="#service">《服务条款》</a>和<a href="javascript:void(0);" data-toggle="modal" data-target="#policy">《隐私协议》</a></label>
      </label>
      <p class="text-danger"><?php $this->ech('error');?></p>
      <button class="btn btn-lg btn-success1 btn-block" type="submit">注册</button>
      <div class="registration">
          已经注册
          <a class="" href="<?php $this->url('account');?>">
              登录
          </a>
      </div>
  </form>
  <div class="modal fade" id="service" tabindex="-1" role="dialog" aria-labelledby="serviceLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h2 class="modal-title">《服务条款》</h2>
                </div>
                <div class="modal-body">
                    <h2>Text in a modal</h2>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                    <h2>Tooltips in a modal</h2>
                    <p><a href="#" class="tooltips" title="" data-original-title="Tooltip">This link</a> and <a href="#" class="tooltips" title="" data-original-title="Tooltip">that link</a> should have tooltips on hover.</p>

                    <hr>
                    <h2>Overflowing text to show scroll behavior</h2>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p><img src="images/pic3.jpg" class="img-responsive" alt="Fountain" class="img-rounded img-responsive"></p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="policy" tabindex="-1" role="dialog" aria-labelledby="policyLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h2 class="modal-title">《隐私协议》</h2>
                </div>
                <div class="modal-body">
                    <h2>Text in a modal</h2>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                    <h2>Tooltips in a modal</h2>
                    <p><a href="#" class="tooltips" title="" data-original-title="Tooltip">This link</a> and <a href="#" class="tooltips" title="" data-original-title="Tooltip">that link</a> should have tooltips on hover.</p>

                    <hr>
                    <h2>Overflowing text to show scroll behavior</h2>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p><img src="images/pic3.jpg" class="img-responsive" alt="Fountain" class="img-rounded img-responsive"></p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
