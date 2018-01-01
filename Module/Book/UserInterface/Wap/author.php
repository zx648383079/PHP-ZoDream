<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@wap.min.css')->registerJsFile('@jquery.min.js');
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>
		<?php echo "{$typename}"; ?>新书_
		<?php echo "{$typename}"; ?>最新小说作品_
		<?php echo "{$typename}"; ?>全部小说作品_
		<?php echo $cfg_webname; ?>
	</title>
	<meta name="keywords" content="<?php echo $typename; ?>新书,<?php echo $typename; ?>最新小说作品,<?php echo $typename; ?>全部小说作品,<?php echo $cfg_webname; ?>"
	/>
	<meta name="description" content="<?php echo $cfg_webname; ?>提供最<?php echo $typename; ?>新书_<?php echo $typename; ?>最新小说作品推荐，<?php echo $typename; ?>全部小说作品在线阅读"
	/>
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=1"
	/>
	<meta name="format-detection" content="telephone=no" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="/css/h_wap.css" media="all" />
</head>

<body>
	<?php include($cfg_templets_dir."/wap/head.htm"); ?>
	<div class="channel">
		<?php echo $channellist; ?>
	</div>
	<form name="From" method="post" action="/wap.php?action=search<?php echo $wxuid; ?>" class="search-form">
		<input type="hidden" name="objectType" value="2" />
		<table>
			<tr>
				<td>
					<input type="text" name="wd" class="text-border vm" value="" />
				</td>
				<td width="8"></td>
				<td width="70">
					<input type="submit" class="btn btn-auto btn-blue vm" value="搜索" />
				</td>
			</tr>
		</table>
	</form>
	<div class="container">
		<h1 class="page-title">
			<?php echo $typename; ?>作品集</h1>
		<div class="mod block book-all-list">
			<div class="bd">
				<ul>
					{dede:datalist}
					<?php
			if(preg_match('#^gb#i',$cfg_soft_lang)) $fields['typename'] = gb2utf8($fields['typename']);
			if(preg_match('#^gb#i',$cfg_soft_lang)) $fields['zuozhe'] = gb2utf8($fields['zuozhe']);
			if(preg_match('#^gb#i',$cfg_soft_lang)) $fields['title'] = gb2utf8(zhangjie($fields['id']));
			if(preg_match('#^gb#i',$cfg_soft_lang)) $fields['overdate'] = gb2utf8(zhuangtai($fields['overdate']));
			?>
						<li class="column-2 ">
							<div class="right">
								<a class="name" href="/wap.php?action=list&id=<?php echo $fields['id'].$wxuid; ?>">
									<?php echo $fields['typename']; ?>
								</a>
								<span style="float:right;font-size:0.8125em;color: #999;">
									<?php echo $fields['overdate']; ?>
								</span>
								<p class="update">
									最新章节：
									<?php echo $fields['title']; ?>
								</p>
								<p class="info">
									作者：
									<?php echo $fields['zuozhe']; ?>
									<span class="words">字数：
										<?php echo $fields['booksize']; ?>
									</span>
								</p>
							</div>
						</li>
						{/dede:datalist}
				</ul>
			</div>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>
	</div>
    <?php $this->extend('./footer')?>
    <?=$this->footer()?>
</body>

</html>