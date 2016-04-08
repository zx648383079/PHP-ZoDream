<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div>
    批量增加点数
    <form>
        请输入点数： <input type="text" name="" value="0">点
        <button type="submit">批量增加</button>
    </form>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>