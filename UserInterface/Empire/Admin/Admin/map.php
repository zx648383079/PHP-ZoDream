<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>
<div id="map">
    <?php
    $this->extend(array(
        'Admin/Menu' => array(
            'column',
            'extend',
            'index',
            'other',
            'shop',
            'system',
            'template',
            'user'
        ))
    );
    ?>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
