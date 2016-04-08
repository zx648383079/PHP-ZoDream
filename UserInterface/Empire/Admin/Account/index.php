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
    <h2 class="text-center">后台登录</h2>
    <form method="POST">
        <input type="email" name="email" value="" required placeholder="邮箱"> </br>
        <input type="password" name="password" value="" required placeholder="密码"> </br>
        <input type="checkbox" name="remember" value="1">记住我</br>
        <button type="submit">登录</button>
    </form>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        function() {?>
            <script>
                require(['../vue/vue.min', 'empire/login']);
            </script>
       <?php }
    )
);
?>