<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div>
    删除下载备份记录
    <form>
        删除截止于  <input type="text" name="" value="2016-04-02 14:59:42">之前的备份记录
        <button type="submit">提交</button>  
    </form>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>