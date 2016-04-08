<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<form action="<?php $this->url('generate');?>" method="POST">
    <div class="panel">
        <h3 class="head">数据库设置</h3>
        <div class="body">
            服务器： <input type="text" name="db[host]" value="localhost" required></br>
            端口： <input type="text" name="db[port]" value="3306"></br>
            数据库： <input type="text" name="db[database]" value="zodream" required></br>
            账号： <input type="text" name="db[user]" value="root" required></br>
            密码： <input type="password" name="db[password]" value=""></br>
            前缀： <input type="text" name="db[prefix]" value="zd_"></br>
            编码： <input type="text" name="db[encoding]" value="utf8">
        </div>
    </div>
    
    <div class="panel">
        <h3 class="head">站点设置</h3>
        <div class="body">
            域名： <input type="text" name="app[host]" value="<?php $this->url('/');?>"></br>
            标题： <input type="text" name="app[title]" value="ZoDream"></br>
            说明： <textarea name="app[description]"></textarea>
        </div>
    </div>
    
    <div class="panel">
        <h3 class="head">后台设置</h3>
        <div class="body">
            用户名： <input type="text" name="admin[user]" value="admin" required></br>
            密码： <input type="password" name="admin[password]" value="" required></br>
            邮箱： <input type="email" name="admin[email]">
        </div>
    </div>
    
    <button type="submit">确认</button>
    <button type="reset">重置</button>
</form>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>