<?php
/** @var $this \Zodream\Template\View */
$this->registerCssFile('@pc.min.css')->registerJsFile('@jquery.min.js');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$this->title?></title>
    <meta name="keywords" content="<?=$this->keywords?>">
    <meta name="description" content="<?=$this->description?>">
    <?= $this->header() ?>
</head>
<body class="bodyph">
<!--header开始-->
<?php $this->extend('./head', ['nav_index' => 99]) ?>
<!--header结束-->
<div class="clear"></div>
<!--body开始-->
<div class="Layout local">当前位置：
    <a href="<?=$this->url('book')?>" title="">新书在线-世间唯有读书高</a>&nbsp;>&nbsp;
    <a href="<?=$this->url('book/home/list')?>">小说书库</a></div>
<div class="clear"></div>
<div class="Layout m_list list">
  <div class="Head">
    <h2>小说列表</h2><span class="j"></span>
    <div class="morelist">
      <div class="more"><a href="<?=$this->url(null, ['status' => 2])?>"
          style="color:#F00;font-weight: 800; text-decoration:underline" title="只看完本小说">只看完本&nbsp;>></a></div>
      <ul>
		<li>（共<b><?=$book_list->getTotal()?></b>部）</li>&nbsp;
		<li><a href="<?=$this->url('book/home/list')?>" style="color:#AA0; text-decoration:underline;font-weight: 800" title="查看全部小说">查看全部小说</a></li>&nbsp;>&nbsp;
          <?php foreach ($cat_list as $item):?>
              <a href="<?=$this->url(null, ['cat_id' => $item->id])?>" <?= $cat_id == $item->id ?  'class="current"' : ''?>"><?=$item->name?></a>
              <em class="ver">| </em>
          <?php endforeach;?>
      </ul>
    </div>
  </div>
  <div class="Con">
    <div class="Left">
      <div class="m_head"> <span class="c">类型</span> <span class="t">书名/章节</span> <span class="w">字数</span> <span class="a">作者</span><span class="z">状态</span></div>
      <ul class="ul_m_list">
          <?php foreach ($book_list as $key => $item):?>
          <li <?=$key % 2 == 1 ? 'class="odd"' : '' ?>>
              <div class="c">[<a href="<?=$item->category->url?>" title="<?=$item->category->name?>" target="_blank"><?=$item->category->name?></a>]</div>
              <div class="title">
                  <div class="t"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a></div>
                  <div class="n">[<a href="#" title="<?=$item->name?>txt下载" target="_blank">下载</a>] <a href="#" target="_blank"></a> </div>
              </div>
              <div class="words">0</div>
              <div class="author"><a href="#" title="<?=$item->author?>作品" target="_blank"><?=$item->author?></a></div>
              <div class="abover"><span><?=$item->status?></span></div>
          </li>
          <?php endforeach;?>
      </ul>
      <div class="bot_more">
        <div class="page_info">每页显示<b>&nbsp;50&nbsp;</b>部，共<b><?=$book_list->getTotal()?></b>部</div>
        <div class="page_num">
          <div><a class="info">第<b><?=$book_list->getIndex()?></b>页/共<?=$book_list->getTotal()?>页</a>
              <a href="<? echo $pagestart; ?>" title="第一页">第一页</a><a href="<? echo $pagepre; ?>" title="上一页">上一页</a></div>
            <div><a href="<? echo $pagenext; ?>" title="下一页">下一页</a>
                <a href="<? echo $pageend; ?>" title="最后一页">最后一页</a></div>
        </div>
      </div>
    </div>
    <div class="Right">
		<div class="r_box cn">
			<div class="head"><h2>小说作家推荐</h2></div>
			<ul>
                <?php foreach ($hot_author as $key => $item):?>
                    <?php if ($key < 1):?>
                        <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                            <span><?=$item->book_count?>/<?=$item->size?></span></li>
                        <li class="first_con">
                            <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                    <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" style="display: inline; background: transparent url(&quot;/images/loading.gif&quot;) no-repeat scroll center center;"></a></div>
                            <div class="a_l">
                                <div><span>作品数:</span><?=$item->book_count?></div>
                                <div><span>总字数:</span><?=$item->size?></div>
                                <div><span>总点击:</span><?=$item->click_count?></div>
                                <div><span>作家推荐:</span>0</div>
                                <div><span>作品推荐:</span><?=$item->recommend_count?></div>
                                <div><span>新书:</span><a href="<?=$item->new_book->url?>" title="<?=$item->new_book->name?>" target="_blank"><?=$item->new_book->name?></a></div>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                            <span><?=$item->book_count?>/</span></li>
                    <?php endif;?>
                <?php endforeach;?>
		
			</ul>
		</div><div class="r_box cmztj cn">
        <div class="head"><h2>热门新书推荐</h2></div>
        <ul>
            <?php foreach ($new_book as $key => $item):?>
                <?php if ($key < 1):?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" style="display: inline; background: transparent url(&quot;/images/loading.gif&quot;) no-repeat scroll center center;"></a></div>
                        <div class="a_l">
                            <div><span>作者:</span><a href="#" title="<?=$item->author?>小说作品" target="_blank"><?=$item->author?></a></div>
                            <div><span>类型:</span><a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->real_name?></a></div>
                            <div><span>点/推:</span><?=$item->click_count?>/<?=$item->recommend_count?></div>
                            <div><?=$item->description?></div>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                <?php endif;?>
            <?php endforeach;?>
           </ul>
      </div>
      <div class="r_box rmwbtj cn">
        <div class="head">
          <h2>热门完本推荐</h2>
        </div>
        <ul>

            <?php foreach ($over_book as $key => $item):?>
                <?php if ($key < 1):?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" style="display: inline; background: transparent url(&quot;/images/loading.gif&quot;) no-repeat scroll center center;"></a></div>
                        <div class="a_l">
                            <div><span>作者:</span><a href="#" title="<?=$item->author?>小说作品" target="_blank"><?=$item->author?></a></div>
                            <div><span>类型:</span><a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->real_name?></a></div>
                            <div><span>点/推:</span><?=$item->click_count?>/<?=$item->recommend_count?></div>
                            <div><?=$item->description?></div>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                <?php endif;?>
            <?php endforeach;?>
		</ul>
      </div>
      <div class="r_box ad ad200"><!--ad-->
      </div>
    </div>
  </div>
</div>
<!--body结束-->
<div class="clear"></div>
<!--footer开始-->

<?php $this->extend('./footer2')?>
<?=$this->footer()?>
<!--footer结束-->
</body></html>