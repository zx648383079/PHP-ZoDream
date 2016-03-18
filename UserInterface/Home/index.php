<?php
use Infrastructure\HtmlExpand;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
?>
<div class="ms-Grid">
	<div class="ms-Grid-row">
		<div style="height: 300px;background-color: #0D414A;">

		</div>
	</div>
</div>
<div class="zd-container">
<div class="ms-Grid">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md8">
			<h3 class="headTitle">最新产品</h3>
            <ul class="ms-List">
                <li class="ms-ListItem">
                    <video id="my-video" class="video-js" controls preload="auto" width="100%" height="366"
                        data-setup="{}">
                        <source src="" type='video/mp4'>
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a web browser that
                            <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                        </p>
                    </video>
                    <h2 class="ms-ListItem-primaryText">123</h2>
                    <div class="ms-ListItem-tertiaryText">123333333333 33333333333333 3333333333333333 33333333333333 3333333333333
                        <a class="ms-Button ms-Button--primary" href="#">查看</a>
                    </div>
                </li>
           </ul>
		</div>
		<div class="ms-Grid-col ms-u-md4">
			<h4 class="headTitle">最新动态</h4>
			<ul class="ms-List">
                <li class="ms-ListItem">
                    <div class="ms-ListItem-primaryText">123</div>
                    <div class="ms-ListItem-actions">
                        <div class="ms-ListItem-action">查看</div>
                    </div>
                </li>
           </ul>
		</div>
	</div>

	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md8">
			<h3 class="headTitle">最受欢迎的博客</h3>
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
		<div class="ms-Grid-col ms-u-md4">
			<iframe width="100%" height="450" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=0&height=450&fansRow=1&ptype=0&speed=0&skin=2&isTitle=1&noborder=0&isWeibo=1&isFans=0&uid=2911585280&verifier=dcb475f3&dpc=1"></iframe>
		</div>
	</div>

	<div class="ms-Grid-row">
        <div class="ms-Grid-col ms-u-md12">
            <h3 class="headTitle">产品展示</h3>
            <div class="slider">
                <ul>
                    <li><img src="assets/images/1.png"></li>
                    <li><img src="assets/images/1.png"></li>
                    <li><img src="assets/images/1.png"></li>
                </ul>
            </div>
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