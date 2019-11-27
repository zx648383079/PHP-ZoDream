<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<ul>
<?php foreach($issue_list as $item):?>
    <li class="issue">
        <div class="question"><?=$item['question']?></div>
        <div class="answer"><?=$item['answer']?></div>
    </li>
<?php endforeach;?>
</ul>