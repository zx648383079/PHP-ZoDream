<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>

<div class="container">
    <div class="row">
        <form method="POST" class="form-horizontal" role="form">
                <div class="form-group">
                    <legend>私信</legend>
                </div>
                <input type="hidden" name="user_id" value="<?php $this->out('user.id');?>">
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea name="content" id="textarea_content" class="form-control" rows="3" required="required"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-9">
                        <button type="submit" class="btn btn-primary">回复</button>
                    </div>
                </div>
        </form>
        
    </div>
    

    <div class="row">
        <div class="list-group">
            <?php foreach ($this->gain('data', array()) as $item) {?>
                <a href="<?php $this->url(['message/send', 'id' => $item['send_id']])?>" class="list-group-item active">
                    <span class="badge"><?php $this->time($item['create_at']);?></span>
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