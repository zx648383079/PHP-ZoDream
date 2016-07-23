<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">字段添加</h3>
      </div>
      <div class="panel-body">
            <form method="post">
                <input name="model_id" type="hidden" value="<?php $this->out('data.model_id');?>" />
                <input name="id" type="hidden" value="<?php $this->out('data.id');?>" />
                <table width="100%" class="table table-striped">
                <tr>
                    <th width="200">模型名称： </th>
                    <td>文章</td>
                </tr>
                <tr>
                    <th><font color="red">*</font> 字段别名： </th>
                    <td><input class="input-text" type="text" name="name" value="" size="30" id="name" required /><div class="onShow">例如：文章标题</div></td>
                </tr>
                <tr>
                    <th><font color="red">*</font> 字段名称： </th>
                    <td><input class="input-text" type="text" id="field" name="field" value="" size="30"  required /><div class="onShow">只能由英文字母、数字和下划线组成，并且仅能字母开头</div>
                </tr>
                <tr>
                    <th><font color="red">*</font> 字段类别： </th>
                    <td><select name="formtype" id="formtype"  required>
                    <option value=""> -- </option>
                                <option value="input" >单行文本</option>
                                <option value="textarea" >多行文本</option>
                                <option value="password" >密码文本</option>
                                <option value="editor"  class="merge_delete">编辑器</option>
                                <option value="select" >下拉选择框</option>
                                <option value="radio" >单选按钮</option>
                                <option value="checkbox" >复选框</option>
                                <option value="image"  class="merge_delete">单图上传</option>
                                <option value="file"  class="merge_delete">文件上传</option>
                                <option value="files"  class="merge_delete">多文件上传</option>
                                <option value="date"  class="merge_delete">日期时间</option>
                                <option value="linkage"  class="merge_delete">联动菜单</option>
                                <option value="merge"  class="merge_delete">组合字段</option>
                                <option value="map"  class="merge_delete">地图字段</option>
                                <option value="fields"  class="merge_delete">多字段组合</option>
                                <option value="wurl"  class="merge_delete">外部链接</option>
                                </select>
                    </td>
                </tr>
                <tr>
                    <th>相关参数： </th>
                    <td><div id="content">
                                </div></td>
                </tr>
                        <tbody id="hidetbody">
                <tr>
                    <th><font color="red">*</font> 字段类型： </th>
                    <td>
                      <select name="type" id="type">
                        <option value="">-</option>
                        <option value="BIGINT">十位整型(BIGINT)</option>
                        <option value="INT">十位整型(INT)</option>
                        <option value="TINYINT">三位整型(TINYINT)</option>
                        <option value="SMALLINT">五位整型(SMALLINT)</option>
                        <option value="MEDIUMINT">八位整型(MEDIUMINT)</option>
                        <option value="">-</option>
                        <option value="DECIMAL">小数类型(DECIMAL)</option>
                        <option value="">-</option>
                        <option value="CHAR">字符类型(CHAR)</option>
                        <option value="VARCHAR">文字类型(VARCHAR)</option>
                        <option value="TEXT">文本类型(TEXT)</option>
                    </select>
                    <div class="onShow">请慎重，一旦创建不能更改</div>
                                </td>
                </tr>
                <tr>
                    <th><font color="red">*</font> 长度值： </th>
                    <td><input class="input-text" type="text" id="length" name="length" value="" size="30" />
                    <div class="onShow">注意长度值不能超界</div></td>
                </tr>
                <tr>
                    <th>字段索引： </th>
                    <td>
                                    <select name="indexkey">
                        <option value="">---</option>
                        <option value="UNIQUE">唯一索引</option>
                        <option value="INDEX">普通索引</option>
                        </select>
                        <div class="onShow">（可选）请慎重，必须理解索引的概念，一旦创建不能更改</div>
                                    </td>
                </tr>
                </tbody>
                        <tr>
                    <th>字段提示： </th>
                    <td><input class="input-text" type="text" name="tips" value="" size="30" /><div class="onShow">显示在字段别名下方作为表单输入提示</div></td>
                </tr>
                <tr>
                    <th>是否前台显示：</th>
                    <td>
                    <input type="radio" checked value="1" name="isshow" /> 显示&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio"  value="0" name="isshow" /> 隐藏			<div class="onShow">前台会员发布时是否显示该字段</div>
                    </td>
                </tr>
                <tbody id="select-ed" style="" />
                <tr>
                    <th>是否必填字段：</th>
                    <td>
                        <input type="radio" checked value="0" name="not_null" /> 选填&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  value="1" name="not_null"/> 必填
                    </td>
                </tr>
                </tbody>
                <tbody id="pattern_data" style="display:none">
                <tr>
                    <th>数据校验正则： </th>
                    <td><input class="input-text" type="text" name="pattern" id="pattern" value="" size="40" />
                    <select id="pattern_select" name="pattern_select">
                        <option value="">常用正则</option>
                        <option value="^[0-9.-]+$">数字</option>
                        <option value="^[0-9-]+$">整数</option>
                        <option value="^[A-Za-z]+$">字母</option>
                        <option value="^[0-9A-Za-z]+$">数字+字母</option>
                        <option value="^[\w\-\.]+@[\w\-\.]+(\.\w+)+$">E-mail</option>
                        <option value="^[0-9]{5,20}$">QQ</option>
                        <option value="^http:\/\/">超级链接</option>
                        <option value="^(1)[0-9]{10}$">手机号码</option>
                        <option value="^[0-9-]{6,13}$">电话号码</option>
                        <option value="^[0-9]{6}$">邮政编码</option>
                    </select><div class="onShow">通过此正则校验表单提交的数据合法性，如果不想校验数据请留空</div>
                    </td>
                </tr>
                <tr>
                    <th>校验提示信息： </th>
                    <td><input class="input-text" type="text" name="errortips" value="" size="30" /><div class="onShow">数据校验未通过的提示信息</div></td>
                </tr>
                </tbody>
                <tr>
                    <th>&nbsp;</th>
                    <td><button class="btn btn-primary" type="submit">提交</button>
                </tr>
                </table>
           </form>
      </div>
</div>



<?php
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        function() {?>
<script type="text/javascript">
require(['admin/field']);
</script>       
     <?php }
    )
);
?>