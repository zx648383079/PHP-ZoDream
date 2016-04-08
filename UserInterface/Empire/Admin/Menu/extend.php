<?php
use Infrastructure\HtmlExpand;
?>
<div>扩展项目</div>
<ul>
    <?php HtmlExpand::getMenu($this->get('data', array())) ?>
</ul>
