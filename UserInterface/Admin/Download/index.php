<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<div>
    增加下载地址前缀:
    <form>
        名称: 
        <input name="urlname" type="text">
        地址: 
        <input name="url" type="text" value="http://">
        下载方式: <select name="downtype">
          <option value="0">HEADER</option>
          <option value="1">META</option>
          <option value="2">READ</option>
        </select>
        <button type="submit">增加</button>
        <button type="reset">重置</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>下载地址前缀管理:</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->gain('data', array()) as $value) {?>
            <tr>
                <td>
                    名称: 
                    <input name="urlname" type="text" value="<?php echo $value['urlname'];?>">
                    地址: 
                    <input name="url" type="text" value="<?php echo $value['url'];?>">
                    <select name="downtype">
                    <option value="0">HEADER</option>
                    <option value="1">META</option>
                    <option value="2">READ</option>
                    </select> 
                    </td>
                <td>[修改][删除]</td>
            </tr>
        <?php }?>
    </tbody>
</table>
<div>  
    下载方式说明：
    HEADER：使用header转向，通常设为这个。
    META：直接转自，如果是FTP地址推荐选择这个。
    READ：使用PHP程序读取，防盗链较强，但较占资源，服务器本地小文件可选择。
</div>
<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>