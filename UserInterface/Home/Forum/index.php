<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
$data = $this->gain('data', array());
?>
<div class="container">
    <?php foreach ($data as $value) { 
        if (0 < $value['parent']) break;
        ?>
        <div class="group">
            <div class="heading"><?php echo $value['name'];?></div>
            
            <?php foreach ($data as $item) { 
                if (0 >= $item['parent'] || $item['parent'] != $value['id']) continue;
                ?>
                <div class="col-md-3">
                    <h3><a href="<?php $this->url('forum/thread/id/'.$item['id']);?>"><?php echo $item['name'];?></a></h3>
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