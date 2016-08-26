<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
$this->registerCssFile('codemirror/codemirror.css');
$this->registerJs('require(["admin/editor"]);');
$this->extend('layout/head');
?>

<div class="container">
    <div class="row">
        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control" type="text" name="file" placeholder="路径" required value="<?=$file?>">
                </div>
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
        <textarea id="code" class="form-control" name="content" rows="25"><?=$content?></textarea>
        </form>
    </div>
</div>

<?=$this->extend('layout/foot')?>
