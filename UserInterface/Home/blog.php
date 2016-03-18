<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Infrastructure\HtmlExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
//$page = $this->get('page');
?>
<div class="zd-container">
<div class="ms-Grid off">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md2">
			<ul class="ms-List">
				<li class="ms-ListItem"><a class="ms-Link">全部</a></li>
				<li class="ms-ListItem"><a class="ms-Link">c#</a></li>
			</ul>
		</div>
		<div class="ms-Grid-col ms-u-md10">
			<ul class="ms-List">
				<li class="ms-ListItem ms-ListItem--image">
					<div class="ms-ListItem-image" style="background-color: #767676;">&nbsp;</div>
					<span class="ms-ListItem-primaryText">Alton Lafferty</span>
					<span class="ms-ListItem-secondaryText">Meeting notes</span>
					<span class="ms-ListItem-tertiaryText">Today we discussed the importance of a, b, and c in regards to d.</span>
					<span class="ms-ListItem-metaText">2:42p</span>
					<div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
					<div class="ms-ListItem-actions">
						<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
						<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--trash"></i></div>
						<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--flag"></i></div>
						<div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--pinLeft"></i></div>
					</div>
				</li>
			</ul>
		</div>
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