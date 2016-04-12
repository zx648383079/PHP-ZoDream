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
     <button>增加信息</button> 
    <button>刷新首页</button> 
    <button>刷新本栏目</button> 
    <button>刷新所有信息JS</button> 
    <button>栏目设置</button> 
    <button>增加采集节点</button> 
    <button>管理采集节点</button> 
</div>
<div>
    [批量归档信息] [更新数据] [预览首页] [预览栏目]
    <form>
        <input type="text" name="keyboard">
        <select name="show">
            <option value="0" selected>不限字段</option>
          </select>
          <button type="submit">搜索</button>
    </form>
</div>

<div>
    已发布 (9)
    待审核 (0)
    归档
</div>

<table>
    <thead>
    <tr>
        <th>ID</th> 
        <th>标题</th> 
        <th>发布者</th> 
        <th>发布时间</th> 
        <th>选择</th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['title'];?></td>
                <td><?php echo $value['username'];?></td>
                <td><?php echo $value['newstime'];?></td>
                <td><input type="checkbox" name="id[]" value="<?php echo $value['id'];?>"></td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">
                <?php $page->pageLink();?>
                 <button type="submit">删除</button> 
                <button type="submit">还原归档</button> 
                <input type="checkbox">
            </th>
        </tr>
        <tr>
            <th colspan="5">备注：发布者红色为会员投稿；信息ID粗体为未生成.</th>
        </tr>
    </tfoot>
</table>
<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>