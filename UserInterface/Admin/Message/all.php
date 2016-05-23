<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
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
                    <legend>群发消息</legend>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea name="content" id="textarea_content" class="form-control" rows="6" required="required"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-9">
                        <button type="submit" class="btn btn-primary">群发</button>
                    </div>
                </div>
        </form>
    </div>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>