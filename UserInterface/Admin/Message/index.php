<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>
<div class="container">
    <div class="row">
        <div class="list-group">
            <?php foreach ($this->gain('data', array()) as $item) {?>
                <a href="<?php $this->url(['message/send', 'id' => $item['send_id']])?>" class="list-group-item active">
                    <span class="badge"><?php $this->ago($item['create_at']);?></span>
                    <h4 class="list-group-item-heading"><?php echo $item['name'];?></h4>
                    <p class="list-group-item-text"><?php echo $item['content']?></p>
                </a>
            <?php }?>
        </div>
        
    </div>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>