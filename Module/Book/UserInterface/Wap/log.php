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
	<title>您的阅读足迹</title>
	<meta name="keywords" content="您的阅读足迹" />
	<meta name="description" content="您的阅读足迹" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
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
		<h1 class="page-title">您的阅读足迹</h1>
		<div class="mod block book-all-list">
			<div class="bd">
				<ul>
					<?php if (!empty($book_list)): ?>
                    <?php foreach ($book_list as $item):?>
                            <li class="column-2 ">
                                <div class="right">
                                    <a class="name" href="<?=$item->wap_url?>"><?=$item->name?></a>
                                    <span style="float:right;font-size:0.8125em;color: #999;"><?=$item->status?></span>
                                    <p class="update">上次看到：<a href="<?=$item->last_log->wap_url?>"><?=$item->last_log->title?></a></p>
                                    <p class="info">作者：<?=$item->author?>  <span class="words">字数：<?=$item->size?></span></p>
                                </div>
                            </li>
                    <?php endforeach;?>
                    <?php else:?>
                        很抱歉！没有找到您的阅读记录哦:)
                    <?php endif;?>
				</ul>
			</div>
		</div>
	</div>
    <?php $this->extend('./footer')?>
    <?=$this->footer()?>
</body>

</html>