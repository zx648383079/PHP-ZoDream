<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div>
    发送邮件
    <form>
        接收会员组<select name="groupid[]" multiple>
        </select>(全选用&quot;CTRL+A&quot;,选择多个用CTRL/SHIFT+点击选择)</br>
        接收会员用户名<input name="username" type="text">(多个用户名“|”隔开)</br>
        每组发送个数<input name="line" type="text" value="100" > </br>
        标题<input name="title" type="text"></br>
        内容(支持html代码)</br>
        <textarea name="msgtext" cols="60" rows="16""></textarea></br>
        <button type="submit">发送</button>
        <button type="reset">重置</button>
    </form>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>