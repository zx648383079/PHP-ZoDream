<?php
use Infrastructure\HtmlExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
?>

<div class="ms-Grid">
	<div class="ms-Grid-row">
		<ul class="ms-Pivot ms-Pivot--tabs ms-Pivot--large">
			<li class="ms-Pivot-link is-selected">My files</li>
			<li class="ms-Pivot-link">Recent</li>
			<li class="ms-Pivot-link">Shared with me</li>
			<li class="ms-Pivot-link ms-Pivot-link--overflow">
				<i class="ms-Pivot-ellipsis ms-Icon ms-Icon--ellipsis"></i>
			</li>
		</ul>
	</div>
</div>


<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>