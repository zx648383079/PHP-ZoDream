<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div>
    批量更改下载/在线地址权限 
    <form>
        >操作数据表(*)： </br>
    操作栏目：<select name="classid">
          <option value=0>所有栏目</option>
        </select>
        (如选择大栏目，将应用于所有子栏目)</br>
    操作字段(*)：<input name="downpath" type="checkbox" value="1">
              下载地址(downpath)<input name="onlinepath" type="checkbox" value="1">
              在线地址(onlinepath)</br>
   权限转换： 
        <input name="dogroup" type="checkbox" value="1">
      原会员组<select name="oldgroupid">
                    <option value="no">不设置</option>
                    <option value="0">游客</option>
                  </select>
     新会员组<select name="newgroupid">
                    <option value="0">游客</option>
                  </select></br>
   点数转换： 
        <input name="dofen" type="checkbox" value="1">
        原点数<input name="oldfen" type="text" value="no" size="6">
        新点数<input name="newfen" type="text" value="0" size="6"></br>
   前缀转换： 
        <input name="doqz" type="checkbox" value="1">
        原前缀<select name="oldqz" >
                  <option value="no">不设置</option>
				  <option value="0">空前缀</option>
                </select>
        新前缀<select name="newqz">
				<option value="0">空前缀</option>
                </select>
        </br>
  地址替换：
        <input name="dopath" type="checkbox" value="1">
      原下载地址字符<input name="oldpath" type="text">
      新下载地址字符<input name="newpath" type="text" >
        </br>
 名称替换：
        <input name="doname" type="checkbox" value="1">
        原下载名称字符<input name="oldname" type="text">
        新下载名称字符<input name="newname" type="text">
        </br>
附加SQL条件：<input name="query" type="text" id="query" size="75">如：title='标题' and writer='作者'</br>
     <button type="submit">提交</button> 
    <button type="reset">重置</button>
    </br>
     说明：如原点数为no，则所有信息的点数都为新点数，如果选项为不设置，则所有信息都为新的值。<br>
        注意：使用此功能，最好备份一下数据，以防万一
    </form>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>