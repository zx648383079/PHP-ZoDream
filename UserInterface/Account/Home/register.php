<?php
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	)), array(
        'zodream/login.css'
    )
);
?>

<div class="login">
    <h2 class="text-center">后台注册</h2>
    <form method="POST">
        <input type="text" name="name" value="" required placeholder="用户名"></br>
        <input type="email" name="email" value="" required placeholder="邮箱"> </br>
        <input type="password" name="password" value="" required placeholder="密码"> </br>
        <input type="password" name="repassword" value="" required placeholder="重复密码"> </br>
        <input type="checkbox" name="agree" value="1" checked>同意"<a href="javascript:0;">服务条款</a>"和"<a href="javascript:0;">隐私权相关政策</a>"
        <button type="submit">注册</button>
    </form>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        function() {?>
            <script>
                require(['admin/login']);
            </script>
       <?php }
    )
);
?>