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
	<title>好看的
		<?php echo $toptypename; ?>小说大全_好看的
		<?php echo $toptypename; ?>小说推荐_
		<?php echo $cfg_webname; ?>
	</title>
    <meta name="keywords" content="<?=$this->keywords?>">
    <meta name="description" content="<?=$this->description?>">
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=1"
	/>
	<meta name="format-detection" content="telephone=no" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <?= $this->header() ?>
</head>

<body>
    <?php $this->extend('./head') ?>
    <div class="channel">
        <?php foreach ($cat_list as $key => $item):?>
            <a class="<?= $key % 3 == 1 ? 'xuanyi' : ''  ?> active" href="<?=$this->url('book/wap/category', ['id' => $item->id])?>"><?=$item->name?></a>
        <?php endforeach;?>
    </div>
    <form name="From" action="<?=$this->url('book/wap/search')?>" class="search-form">
        <table>
            <tr>
                <td>
                    <input type="text" name="keywords" class="text-border vm" value="" placeholder="搜索"/>
                </td>
                <td width="8"></td>
                <td width="70">
                    <input type="submit" class="btn btn-auto btn-blue vm" value="搜索" />
                </td>
            </tr>
        </table>
    </form>
	<div class="container">
		<h1 class="page-title">好看的
			<?php echo $toptypename; ?>小说大全</h1>
		<div class="mod block book-all-list">
			<div class="hd">
				<div class="filter even">
					<span>类型：</span>
                    <a href="<?=$this->url(null, ['cat_id' => null])?>" <?= $cat_id < 1 ? 'class="current"' : ''?>">全部</a>
					<?php foreach ($cat_list as $item):?>
                        <a href="<?=$this->url(null, ['cat_id' => $item->id])?>" <?= $cat_id == $item->id ?  'class="current"' : ''?>"><?=$item->name?></a>
                    <?php endforeach;?>
				</div>
				<div class="filter">
					<span>排序：</span>
                    <?php foreach ($sort_list as $key => $item):?>
                        <a href="<?=$this->url(null, ['sort' => $key])?>" <?= $key == $sort ? 'class="current"' : ''?>"><?=$item?></a>
                    <?php endforeach;?>
				</div>
				<div class="filter even">
					<span>是否完结：</span>
                    <a href="<?=$this->url(null, ['status' => null])?>" <?= $status < 1 ? 'class="current"' : ''?>">全部</a>
                    <a href="<?=$this->url(null, ['status' => 2])?>" <?= $status == 2 ? 'class="current"' : ''?>">已完本</a>
                    <a href="<?=$this->url(null, ['status' => 1])?>" <?= $status == 1 ? 'class="current"' : ''?>">连载中</a>
				</div>
			</div>
			<div class="bd">
				<ul>
                    <?php foreach ($book_list as $item):?>
                        <li class="column-2 ">
                            <a class="name" href="<?=$item->wap_url?>"><?=$item->name?></a>
                            <span style="float:right;font-size:0.8125em;color: #999;"><?=$item->status?></span>
                            <?php if ($item->last_chapter):?>
                                <p class="update">最新章节：
                                    <a href="<?=$item->last_chapter->wap_url?>"><?=$item->last_chapter->title?></a>
                                </p>
                            <?php endif;?>
                            <p class="info">作者：
                                <a href="<?=$item->wap_url?>" class="author"><?=$item->author?></a>
                                <span class="words">字数：<?=$item->size?></span>
                            </p>
                        </li>
                    <?php endforeach;?>
				</ul>
			</div>
		</div>
		<div class="slide-ad">
			<!--广告-->
		</div>
		<div class="mod page">
			<?=$book_list->getLink()?>
		</div>
	</div>
    <?php $this->extend('./footer')?>
    <?=$this->footer()?>
</body>

</html>