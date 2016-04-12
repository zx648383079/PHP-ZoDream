<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div>
    商城参数设置
<form method="post">
  指定使用商城功能的数据表 
  </br>
    购买流程<select name="buystep">
	    <option value="0">购物车 &gt; 联系方式+配送方式+支付方式 &gt; 确认订单 &gt; 提交订单</option>
		<option value="1">购物车 &gt; 联系方式+配送方式+支付方式 &gt; 提交订单</option>
		<option value="2">联系方式+配送方式+支付方式 &gt; 提交订单</option>
	    </select>	  </br>
<input name="shoppsmust" type="checkbox" value="1">
      显示配送方式
      <input name="shoppayfsmust" type="checkbox" value="1">
      显示支付方式 (提交订单时不显示且为非必选项)</br>
提交订单权限<select name="shopddgroupid" >
              <option value="0">游客</option>
			  <option value="1">会员才能提交订单</option>
            </select></br>
购物车最大商品数<input name="buycarnum" type="text" >(0为不限，为1时购物车会采用替换原商品方式)</br>
单商品最大购买数<input name="singlenum" type="text">(0为不限，限制订单里单个商品最大购买数量)</br>
满多少金额免运费<input name="freepstotal" type="text" >
            元(0为无免运费)</br>
 购物车支持附加属性<input type="radio" name="haveatt" value="1">
开启
  <input type="radio" name="haveatt" value="0">
关闭（加入商品可用“addatt”数组变量传递，例如：&amp;addatt[]=蓝色） </br>
会员可自己取消订单的时间<input name="dddeltime" type="text" >
            分钟 (超过设定时间会员自己不能删除订单，0为不可删除)</br>
库存减少设置<select name="cutnumtype">
            <option value="0">下订单时减少库存</option>
            <option value="1">下订单并支付时减少库存</option>
          </select>   </br>
未付款多少时间后还原库存<input name="cutnumtime" type="text">
            分钟 (0为不限，超过设定时间自动取消订单，并还源库存)</br>
是否提供发票<input name="havefp" type="checkbox" value="1">
            是,收取 
            <input name="fpnum" type="text" >
            % 的发票费 </br>
发票名称<br>
          <br>
(一行一个，比如：办公用品)
<textarea name="fpname" cols="38" rows="8" ></textarea></br>
订单必填项
<input name="ddmustf[]" type="checkbox" value="truename">
            姓名
            <input name="ddmustf[]" type="checkbox" value="oicq">
            QQ
            <input name="ddmustf[]" type="checkbox" value="msn">
            MSN
            <input name="ddmustf[]" type="checkbox" value="email">
            邮箱
            <input name="ddmustf[]" type="checkbox" value="mycall">
            固定电话
            <input name="ddmustf[]" type="checkbox" value="phone">
            手机
            <input name="ddmustf[]" type="checkbox" value="address">
            联系地址
            <input name="ddmustf[]" type="checkbox" value="zip">
邮编
<input name="ddmustf[]" type="checkbox" value="signbuild">
            标志建筑
            <input name="ddmustf[]" type="checkbox" value="besttime">
            送货最佳时间
            <input name="ddmustf[]" type="checkbox" value="bz"> 
            备注 </br>
  <button type="submit">提交</button>
  <button type="reset">重置</button>
</form>

</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>