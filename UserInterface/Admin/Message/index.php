<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');
?>
<div class="container">
    <div class="row">
        <div class="list-group">
            <?php foreach ($data as $item) :?>
                <a href="<?=Url::to(['message/send', 'id' => $item['send_id']])?>" class="list-group-item active">
                    <span class="badge"><?=$this->ago($item['create_at'])?></span>
                    <h4 class="list-group-item-heading"><?=$item['name']?></h4>
                    <p class="list-group-item-text"><?=$item['content']?></p>
                </a>
            <?php endforeach;?>
        </div>
        
    </div>
</div>

<?=$this->extend('layout/footer')?>