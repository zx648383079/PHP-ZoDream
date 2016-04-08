<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>
<div id="addInfo">
    <?php $this->extend('Admin.Menu.index');?>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
