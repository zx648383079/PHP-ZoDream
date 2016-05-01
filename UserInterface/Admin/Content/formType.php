<?php
$type = $this->get('type');
if ($type == 'input') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
	<tbody>
	<tr> 
      <td width="100">长度：</td>
      <td><input type="text" class="input-text" size="10" value="400" name="setting[size]"><font color="gray">px</font></td>
    </tr>
	<tr>
      <td>默认值：</td>
      <td><input type="text" class="input-text" size="30" name="setting[default]"></td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'wurl') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
	<tbody>
	<tr>
      <td width="100">长度 ：</td>
      <td><input type="text" class="input-text" size="10" value="150" name="setting[size]"><font color="gray">px</font></td>
    </tr>
	<tr>
      <td></td>
      <td>该字段只能在内容模型中有效，字符类型请设置文本或文字类型</td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'password') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
	<tbody>
	<tr>
      <td width="100">长度：</td>
      <td><input type="text" class="input-text" size="10" value="150" name="setting[size]"><font color="gray">px</font></td>
    </tr>
	<tr>
      <td>默认值 ：</td>
      <td><input type="text" class="input-text" size="30" name="setting[default]"></td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'textarea') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
	<tbody>
	<tr>
      <td width="100"> 宽度 ：</td>
      <td><input type="text" class="input-text" size="20" value="400" name="setting[width]">
      <font color="gray">px</font>
      </td>
    </tr>
	<tr>
      <td>高度 ：</td>
      <td><input type="text" class="input-text" size="20" value="90" name="setting[height]">
      <font color="gray">px</font>
      </td>
    </tr>
	<tr>
      <td>默认值 ：</td>
      <td><textarea name="setting[default]" rows="2" cols="30" class="text"></textarea></td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'editor') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
    <tbody>
	<tr>
      <td width="100"> 宽度 ：</td>
      <td><input type="text" class="input-text" size="10" value="300" name="setting[width]">
      <font color="gray">%</font>
      </td>
    </tr>
    <tr>
      <td>高度 ：</td>
      <td><input type="text" class="input-text" size="10" value="100" name="setting[height]">
      <font color="gray">px</font>
      </td>
    </tr>
    <tr>
      <td>类型 ：</td>
      <td><input type="radio" value=1 name="setting[type]"> 完整模式&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="radio" value=0 name="setting[type]"> 精简模式
      </td>
    </tr>
	<tr>
      <td>默认值 ：</td>
      <td><textarea name="setting[default]" rows="2" cols="30" class="text"></textarea></td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'select' || $type == 'radio') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
	<tbody>
	<tr>
      <td width="170">选项列表 ：</td>
      <td><textarea name="setting[content]" style="width:195px;height:100px;" class="text"></textarea>
      <font color="gray">格式：选项名称1|选项值1 (回车换行)</font>
      </td>
    </tr>
	<tr>
      <td>默认选中值 ：</td>
      <td><input type="text" class="input-text" style="width:200px;"  name="setting[default]"></td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'checkbox') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
	<tbody>
	<tr>
      <td width="170">选项列表 ：</td>
      <td><textarea name="setting[content]" style="width:195px;height:100px;" class="text"></textarea>
      <font color="gray">格式：选项名称1|选项值1 (回车换行)</font>
      </td>
    </tr>
	<tr>
      <td>默认选中值 ：</td>
      <td><input type="text" class="input-text" style="width:200px;" name="setting[default]">
	  <br><font color="gray">多个选中值以分号分隔“,”，格式：选中值1,选中值2</font>
	  </td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'image') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
    <tbody>
	<tr>
      <td width="100"> 宽度 ：</td>
      <td><input type="text" class="input-text" size="10" value="200" name="setting[width]">
      <font color="gray">px</font>
      </td>
    </tr>
	<tr>
      <td>高度 ：</td>
      <td><input type="text" class="input-text" size="10" value="160" name="setting[height]">
      <font color="gray">px</font>
      </td>
    </tr>
	<tr>
      <td>大小 ：</td>
      <td><input type="text" class="input-text" size="10" value="2" name="setting[size]">
      <font color="gray">MB</font>
      </td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'file' || $type == 'files') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
    <tbody>
	<tr>
      <td width="100">格式 ：</td>
      <td><input type="text" class="input-text" size="50" name="setting[type]">
      <font color="gray">多个格式以,号分开，如：zip,rar,tar</font>
      </td>
    </tr>
    <tr>
      <td>大小 ：</td>
      <td><input type="text" class="input-text" size="10" value="2" name="setting[size]">
      <font color="gray">MB</font>
      </td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'date') {
?>
    <table width="98%" cellspacing="1" cellpadding="2">
    <tbody>
	<tr>
      <td width="100"> 宽度 ：</td>
      <td><input type="text" class="input-text" size="7" value="150" name="setting[width]">
      <font color="gray">px</font>
      </td>
    </tr>
	<tr>
      <td>格式 ：</td>
      <td><input type="text" class="input-text" size="25" value="%Y-%m-%d %H:%M:%S" name="setting[type]">
      <font color="gray">格式%Y-%m-%d %H:%M:%S表示2001-02-13 11:20:20</font>
      </td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'map') {
?>
	<table width="98%" cellspacing="1" cellpadding="2">
    <tbody>
	<tr>
      <td width="100">默认城市</td>
      <td><input type="text" name="setting[city]" class="input-text" size=20></td>
    </tr>
    </tbody>
	</table>
<?php 
}
if ($type == 'fields') {
?>
	<table width="98%" cellspacing="1" cellpadding="2">
    <tbody>
	<tr>
      <td width="100">多字段组合 ：</td>
      <td><textarea name="setting[content]" style="width:444px;height:200px;" class="text"></textarea>
      </td>
    </tr>
	<tr>
      <td>格式 ：</td>
      <td><font color="gray">	{字段名称}[介绍]，例如：{shi}室，{ting}厅，{wei}卫</font><br><div class="onShow">注意：被组合的字段必须是单行、多行、密码文本、下拉选择框、单选按钮、复选框</div></td>
    </tr>
    </tbody>
	</table>
<?php }?>