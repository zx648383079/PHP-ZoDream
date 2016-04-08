<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>

<div>
    清理会员
    <form>
        用户名包含字符：<input name="username" type="text"></br>
        邮箱地址包含字符：<input name="email" type="email"></br>
        用户ID 介于：<input name="startuserid" type="text">
        -- 
        <input name="enduserid" type="text"></br>
        所属会员组：<select name="groupid">
          <option value="0">不限</option>
        </select></br>
        注册时间 介于：<input name="startregtime" type="text">
        -- 
        <input name="endregtime" type="text">(格式：2011-01-27)</br>
        点数 介于：<input name="startuserfen" type="text">
        -- 
        <input name="enduserfen" type="text"></br>
        帐户余额 介于：<input name="startmoney" type="text">
        -- 
        <input name="endmoney" type="text"></br>
        是否审核：<input name="checked" type="radio" value="0" checked>
        不限 
        <input name="checked" type="radio" value="1">
        已审核会员 
        <input name="checked" type="radio" value="2">
        未审核会员 </br>
        <button type="submit">删除会员</button>
    </form>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>