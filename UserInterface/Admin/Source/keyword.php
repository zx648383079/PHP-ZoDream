<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>管理内容关键字分类</button>
选择分类： 
<select name="fcid">
<option value="0">显示所有分类</option>
</select>
<div>
    增加关键字:
    <form>
        关键字: <input type="text" name="name" value="">
       链接地址:<input type="text" name="url" value="http://">
       所属分类:
        <select name="cid">
          <option value="0">不隶属分类</option>
        </select> 
        <button type="submit">增加</button>
        <button type="reset">重置</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>关键字</th>
        <th>操作</th>
    </tr>
    </thead>
     <tbody>
        <?php foreach($page->getPage() as $item) {?>
            <tr>
                <form>
                    <td>
                       关键字: 
        <input name="keyname" type="text" value="<?php echo $item['keyname'];?>">
        链接地址: 
        <input name="keyurl" type="text" id="keyurl" value="<?php echo $item['keyurl'];?>" size="30">
        所属分类: 
        <select name="cid">
          <option value="0">不隶属分类</option>
        </select>
                    </td>
                    <td>
                        <button type="submit">修改</button>
                        <button>删除</button>
                    </td>
                </form>
            </tr>
        <?php }?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="2">
                <?php $page->pageLink();?>
            </th>
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