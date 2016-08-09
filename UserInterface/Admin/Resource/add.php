<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
    'layout' => array(
        'head'
    )), array(
        'codemirror/codemirror.css'
    )
);
?>

<div class="container">
    <div class="row">
        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control" type="text" name="file" placeholder="路径" required value="<?php $this->out('file');?>">
                </div>
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
        <textarea id="code" class="form-control" name="content" rows="25"><?php $this->out('content');?></textarea>
        </form>
    </div>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        '!js require(["admin/editor"]);'
    )
);
?>
