<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerCssFile('@forum.css')
    ->registerJsFile('@forum.min.js');
?>

<?php foreach(range(1, 20) as $item):?>
    <div class="thread-item">
        <div class="name">
            <i class="fa fa-file"></i>
            [
            <a href="">求助</a>
            ]
            <a href="<?=$this->url('./thread', ['id' => $item])?>">123313213213</a>
        </div>
        <div class="time">
            <em>admin</em>
            <em>1分钟</em>
        </div>
        <div class="count">
            <em>1</em>
            <em>2</em>
        </div>
        <div class="reply">
            <em>admin</em>
            <em>1分钟</em>
        </div>
    </div>
<?php endforeach;?>
