<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<button>管理支付记录</button>
<button管理支付接口></button>
<div>
    支付参数配置
    <form>
        一元可购买：<input name="paymoneytofen" type="text" value="">
        点数 </br>
        最小支付金额：<input name="payminmoney" type="text" >元 </br>
        <button type="submit">设 置</button>
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