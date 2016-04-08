<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加图片信息</button>
<button>管理图片信息分类</button>
分类：
<select name="classid">
<option value="0">所有类别</option>
</select>

<table>
    <thead>
    <tr>
         <th>ID</th> 
         <th>预览</th>
        <th>操作</th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['picid'];?></td>
                <td><?php echo $value['title'];?></td>
                <td>[修改][删除]<input name="picid[]" type="checkbox" id="picid[]" value="<?php echo $value['picid'];?>"></td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3"><?php $page->pageLink();?><button type="submit">批量删除</button></th>
        </tr>
        <tr>
            <th colspan="3">JS调用方式：&lt;script src= 
        
        d/js/pic/pic_图片信息ID.js&gt;&lt;/script&gt;</th>
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