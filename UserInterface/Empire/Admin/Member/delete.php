<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div>
    删除站内短消息
    <form>
        消息类型<select name="msgtype">
          <option value="0">前台全部消息</option>
		  <option value="2">只删除前台系统消息</option>
		  <option value="1">后台全部消息</option>
		  <option value="3">只删除后台系统消息</option>
        </select></br>
    发件人<input name="from_username" type="text">
        <input name="fromlike" type="checkbox" value="1" checked>
        模糊匹配 (不填为不限)</br>
    收件人<input name="to_username" type="text" id="to_username">
        <input name="tolike" type="checkbox" value="1" checked>
        模糊匹配(不填为不限)</br>
    包含关键字<input name="keyboard" type="text"> 
        <select name="keyfield">
          <option value="0">检索标题和内容</option>
          <option value="1">检索信息标题</option>
          <option value="2">检索信息内容</option>
        </select>
        (多个请用&quot;,&quot;格开)</br>
    时间 删除从 
        <input name="starttime" type="text">
        到 
        <input name="endtime" type="text">
        之间的短消息 </br>
        <button type="submit">批量删除</button>
    </form>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>