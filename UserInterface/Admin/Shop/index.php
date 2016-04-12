<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<form>
    搜索: <input name="keyboard" type="text"> 
        <select name="show">
          <option value="1">订单号</option>
          <option value="2">用户名</option>
		  <option value="3">收货人姓名</option>
		  <option value="4">收货人邮箱</option>
		  <option value="5">收货人地址</option>
        </select> 
        <select name="checked">
          <option value="0">不限订单状态</option>
          <option value="1">已确认</option>
          <option value="9">未确认</option>
		  <option value="2">取消</option>
		  <option value="3">退货</option>
        </select> 
        <select name="outproduct">
          <option value="0">不限发货状态</option>
          <option value="1">已发货</option>
          <option value="9">未发货</option>
		  <option value="2">备货中</option>
        </select>
        <select name="haveprice">
          <option value="0">不限付款状态</option>
          <option value="1">已付款</option>
          <option value="9">未付款</option>
        </select>
        <select name="myorder">
          <option value="0">订单时间降序</option>
          <option value="1">订单时间升序</option>
          <option value="2">商品金额降序</option>
          <option value="3">商品金额升序</option>
          <option value="4">商品点数降序</option>
          <option value="5">商品点数升序</option>
          <option value="6">优惠金额升序</option>
          <option value="7">优惠金额降序</option>
        </select> </br>
        时间:从 
        <input name="starttime" type="text" >
        到 
        <input name="endtime" type="text">
        止的订单 
        <button type="submit">搜索</button>
</form>

<table>
    <thead>
    <tr>
         <th>选择</th> 
        <th>编号(点击查看)</th> 
        <th>订购时间</th> 
        <th>订购者</th> 
        <th>总金额</th> 
        <th>支付方式</th> 
        <th>状态</th>  
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><input type="checkbox" name="ddid[]" value="<?php echo $value['ddid'];?>"></td>
                <td><?php echo $value['ddno'];?></td>
                <td><?php echo $value['ddtime'];?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th><input type="checkbox"></th>
            <th colspan="6">
                <select name="checked">
        <option value="1">确认</option>
        <option value="2">取消</option>
        <option value="3">退货</option>
        <option value="0">未确认</option>
      </select>
      <button type="submit">设置订单状态</button>
        <select name="outproduct">
          <option value="1">已发货</option>
          <option value="2">备货中</option>
          <option value="0">未发货</option>
        </select> 
        <button type="submit">设置发货状态</button>
        <select name="haveprice">
          <option value="1">已付款</option>
          <option value="0">未付款</option>
        </select> 
        <button type="submit">设置付款状态</button>
            <select name="cutmaxnum">
            <option value="1">还原库存</option>
            <option value="0">减少库存</option>
            </select>
            <button type="submit">设置库存</button>
            <button type="submit">删除订单</button>
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="6">
                <?php $page->pageLink();?>
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="6">
                订购者为灰色,则为非会员购买；退货不自动还原库存，需手动还原库存；已还原过的库存系统不会重复还原。
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