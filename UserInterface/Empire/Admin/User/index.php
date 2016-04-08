<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div>
    修改资料
</div>
<form method="POST">
    <div class="table">
        <div class="row">
            <div>
                用户名：
            </div>
            <div>
                <?php $this->ech('name');?>
            </div>
        </div>
        <div class="row">
            <div>
                原密码：
            </div>
            <div>
                <input type="password" name="oldpassword" required placeholder="原密码">
            </div>
        </div>
        <div class="row">
            <div>
               新密码: 
            </div>
            <div>
                <input type="password" name="password" placeholder="新密码">
            </div>
        </div>
        <div class="row">
            <div>
               重复新密码： 
            </div>
            <div>
                <input type="password" name="repassword" required placeholder="重复新密码">
            </div>
        </div>
        <div>
            <button type="submit">提交</button>
            <button type="reset">重置</button>
        </div>
    </div>
</form>
<p>说明：密码设置6位以上，且密码不能包含：$ 
        &amp; * # &lt; &gt; ' &quot; / \ % ; 空格</p>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>