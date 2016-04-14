<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Infrastructure\HtmlExpand;
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
$data = $this->get('data', array());
?>
<div class="container">
    <?php foreach ($data as $value) { 
        if (0 < $value['parent']) break;
        ?>
        <div>
            <div><?php echo $value['name'];?></div>
            <?php foreach ($data as $item) { 
                if (0 >= $item['parent'] || $item['parent'] != $value['id']) continue;
                ?>
                <div>
                    <div><?php echo $item['name'];?></div>
                </div>
            <?php }?>
        </div>
    <?php }?>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>