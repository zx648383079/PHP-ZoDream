<?php
use Infrastructure\HtmlExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
?>
<div class="zd-container">
<div class="ms-Grid off">
	<div class="ms-Grid-row">
        <div class="ms-Grid-col ms-u-md12">
            <ul class="ms-Pivot ms-Pivot--tabs ms-Pivot--large">
                <li class="ms-Pivot-link is-selected">全部</li>
                <li class="ms-Pivot-link">游戏</li>
                <li class="ms-Pivot-link">应用</li>
            </ul>
        </div>
        <div class="ms-Grid-col ms-u-md3">
            <div class="zd-ListItem">
                <img class="zd-image" src="assets/images/1.png">
                <h2 class="zd-title" class="ms-ListItem-primaryText">123</h2>
                <p class="zd-text">123333333333333 33333333333333 33333333333333333 33333333333333333333333
                </p>
            </div>
        </div>
	</div>
</div>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        'jquery.Pivot',
        function(){ ?>
 <script type="text/javascript">
 $('.ms-Pivot').Pivot();
 </script>
     <?php }
    )
);
?>