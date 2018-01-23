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
<body>
<?php $this->extend('./head') ?>

<div class="clear"></div>
<!--body开始-->
<div class="Layout local">当前位置：<a href="<?=$this->url('./')?>" title="">新书在线-世间唯有读书高</a>
    > <a href="<?=$author->url?>" title="<?=$author->name?>作品集"><?=$author->name?>作品集</a></div>
<div class="clear"></div>
<div class="Layout no_h">
  <div class="Con jj">
    <div class="Left">
      <div class="p_box">
                <div class="pic">
                    <a href="<?=$author->url?>" title="<?=$author->name?>">
                        <img class="lazy" src="/assets/images/blank.gif" data-original="<?=$author->avatar?>" alt="<?=$author->name?>" /></a></div>
        <div class="rmxx_box">
          <h2>热门小说推荐</h2>
          <div class="a_box HOT_BOX">
              <?php foreach ($hot_book as $item) : ?>
                  <li><a href="<?=$item->url?>"><?=$item->name?></a></li>
              <?php endforeach;?>
                      
          </div>
        </div>
      </div>
      <div class="j_box">
        <div class="title">
          <h2><a href="<?=$author->url?>" title="<?=$author->name?>"><?=$author->name?></a></h2>
        </div>
		<div class="info">
			<ul>
			    <li><span>作品数：</span><a><?=$author->book_count?></a></li>
					<li class='lb'><span>总字数：</span><?=$author->size?></li>
					<li><span>总点击：</span><font id='cms_clicks'><?=$author->click_count?></font></li>
					<li><span>月点击：</span><font id='cms_mclicks'><?=$author->month_click?></font></li>
					<li class='zd'><span>周点击：</span><font id='cms_wclicks'><?=$author->week_click?></font></li>
					<li><span>总推荐：</span><font id='cms_ready_1'><?=$author->recommend_count?></font></li>
					<li><span>月推荐：</span><font id='cms_favorites'><?=$author->month_recommend?></font></li>
					<li class='wj'><span>周推荐：</span><?=$author->week_recommend?></li>
			</ul>
          <div class="praisesBTN"><a href="javascript:ajax_praise('<?=$author->id?>');" title="推荐作家！"><font id="cms_praises"><?=$author->recommend_count?></font> 推荐作家！</a></div>
        </div>
        <div class="words">
			 <p>简介：<br/><a href="<?=$author->url?>" title="<?=$author->name?>新书"><?=$author->name?>新书</a><?=$author->new_book->name?>已经更新了，本站提供
                 <a href="<?=$author->url?>" title="<?=$author->name?>最新小说"><?=$author->name?>最新小说</a>
                 作品<?=$author->new_book->name?>全文在线阅读以及<?=$author->name?>已经完本的小说<?=$author->over_book->name?>txt全集免费下载，
                 <a href="<?=$author->url?>" title="<?=$author->name?>全部小说"><?=$author->name?>全部小说</a>作品txt电子书免费下载，
                 <a href="<?=$author->url?>" title="<?=$author->name?>小说"><?=$author->name?>小说</a>全集免费在线阅读，尽在新书小说网-搜刮好东西。</p>
        </div>
        <div class="read_btn">
          <div class="btn" style="width:328px"><a href="javascript:addBookmark('<?=$author->name?>新书-新书小说网-搜刮好东西')" class="sc" title="加入收藏夹" style="margin-right:2px">加入收藏夹</a></div>
        </div>
        <div class="vote"><!--AD-->
        </div>
      </div>
    </div>
    <div class="Right">
      <div class="r_box tab">
        <div class="head"> <a class="l active" showBOX="BOX1">其他作家推荐</a></div>
        <div class="box BOX1" style="display:block;">
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
                          <span><?=$item->book_count?>/<?=$item->click_count?></span></li>
                  <?php endif;?>
              <?php endforeach;?>
		</ul>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clear"></div>
<div class="Layout bw">
  <div class="Head">
    <h2><?=$author->name?>作品集</h2>
    <span class="j"></span>
  </div>
  <div class="Con">
    <div class="Left">      
		<?php foreach ($book_list as $item):?>
            <div class="bw_box">
                <div class="t"><a href="<?=$item->url?>" target="_blank" title="<?=$item->name?>在线阅读txt下载"><?=$item->name?></a>
                    <span>（<?=$item->size?>字-<?=$item->status?>）</span></div>
                <div class="pic"><a href="<?=$item->url?>" target="_blank" title="<?=$item->name?>在线阅读txt下载">
                        <img src="<?=$item->cover?>" alt="<?=$item->name?>在线阅读txt下载"></a></div>
                <div class="a_l">
                    <div class="a"><span>作者:</span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>新书"><?=$item->author->name?></a></div>
                    <div class="l"><span>类型:</span><a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->name?>小说"><?=$item->category->name?></a></div>
                    <div class="l"><span>下载:</span><a href="<?=$item->download_url?>" target="_blank" title="<?=$item->name?>txt下载">txt下载</a></div>
                </div>
                <div class="info">
                    <p>简介：<?=$item->description?></p>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="Right">
      <div class="r_box qldzb">
        <div class="head">
          <h2>月度点击榜</h2>
        </div>
        <ul>
            <?php foreach ($month_click as $key => $item):?>
                <?php if ($key < 1):?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" style="display: inline; background: transparent url(&quot;/images/loading.gif&quot;) no-repeat scroll center center;"></a></div>
                        <div class="a_l">
                            <div class="a_l">
                                <div class="a"><span>作者:</span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></div>
                                <div class="l"><span>类型:</span>[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说">
                                        <?=$item->category->real_name?></a>]</div>
                                <div class="l"><span>下载:</span><a href="<?=$item->download_url?>" target="_blank" title="<?=$item->name?>txt下载">txt下载</a></div></div>
                            <div class="info"><p><a href="<?=$item->url?>" target="_blank">简介：<?=$item->description?>……</a></p></div>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                <?php endif;?>
            <?php endforeach;?>
		</ul>
      </div>
    </div>
  </div>
<div align="left">
<br/><h3>阅读提示：</h3><br/>
您现在浏览的是<?=$author->name?>的小说作品集，如果在阅读的过程中发现我们的转载有问题，请及时与我们联系！<br/>特别提醒的是：小说作品一般都是根据作者写作当时的思考方式虚拟出来的，其情节虚构的成份比较多，切勿当真或模仿！ 
</div>
</div>
<div align="center"><!-- ad --></div>

<!--body结束-->
<div class="clear"></div>
<!--footer开始-->
<?php $this->extend('./footer2')?>
<?=$this->footer()?>
</body>
</html>