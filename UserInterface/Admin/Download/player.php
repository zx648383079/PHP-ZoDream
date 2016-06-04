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
    增加播放器:
    <form>
        播放器名称:<input name="player" type="text" value="">文件名:e/DownSys/play/ 
        <input name="filename" type="text" value="">[选择] 说明:<input name="bz" type="text">
        <button type="submit">增加</button>
    </form>
</div>

<table>
    <thead>
    <tr>
         <th>ID</th> 
        <th>播放器名称</th> 
        <th>文件名</th> 
        <th>说明</th> 
        <th>操作</th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('data', array()) as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td>
                    <input name="player" type="text" value="<?php echo $value['player'];?>">    
                </td>
                <td>
                     e/DownSys/play/<input name="filename" type="text" value="<?php echo $value['filename'];?>">[选择]
                </td>
                <td>
                     <input name="bz" type="text" value="<?php echo $value['bz'];?>">
                </td>
                <td>[修改][删除]</td>
            </tr>
        <?php }?>
    </tbody>
</table>
<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>