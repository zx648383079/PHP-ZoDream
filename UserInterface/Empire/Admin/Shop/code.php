<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加优惠码</button>
<button>批量增加优惠码</button>
<button>管理过期优惠码</button>
<form>
    搜索： 
        <select name="show">
          <option value="1">优惠码名称</option>
          <option value="2">优惠码</option>
          <option value="3">金额</option>
        </select>
		<input name="keyboard" type="text"> 
        <select name="pretype">
          <option value="0">不限类型</option>
          <option value="1">减金额的优惠码</option>
          <option value="2">百分比的优惠码</option>
        </select>
        <select name="reuse">
          <option value="0">不限使用</option>
          <option value="1">一次性使用</option>
          <option value="2">可重复使用</option>
        </select> 
        <button type="submit">搜索</button>
</form>

<table>
    <thead>
    <tr>
        <th></th> 
        <th>ID</th> 
        <th>优惠码名称</th> 
        <th>优惠码</th> 
        <th>金额(元)</th> 
        <th>重复使用</th> 
        <th>操作</th>   
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><input type="checkbox" name="id[]" value="<?php echo $value['id'];?>"></td>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['prename'];?></td>
                <td><?php echo $value['precode'];?></td>
                <td></td>
                <td><?php echo $value['reuse'];?></td>
                <td>[修改] [删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th><input type="checkbox"></th>
            <th colspan="6">
                <?php $page->pageLink();?>
                <button>删除选中</button>
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