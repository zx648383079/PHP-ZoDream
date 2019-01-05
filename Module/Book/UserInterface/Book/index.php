<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $book->name;
$this->extend('layouts/header');
?>
<div class="clear"></div>
<!--body开始-->
<div class="box-container local">当前位置：
    <a href="<?=$this->url('./')?>" title=""><?=$site_name?></a> >
    <a href="<?=$cat->url?>"><?=$cat->real_name?>小说</a>
</div>
<div class="clear"></div>
<div class="box-container no_h">
  <div class="Con jj">
    <div class="Left">
      <div class="p_box">
                <div class="pic"><a href="<?=$book->url?>" title="<?=$book->name?>小说"><img class="lazy" src="<?=$book->cover?>" alt="<?=$book->name?>小说" /></a></div>
        <div class="rmxx_box">
          <h2>其他热门小说</h2>
          <div class="a_box HOT_BOX">
              <?php foreach ($hot_book as $item) : ?>
                  <a href="<?=$item->url?>"><?=$item->name?></a>
              <?php endforeach;?>
                      
          </div>
        </div>
      </div>
      <div class="j_box">
        <div class="title">
          <h2><?=$book->name?></h2>
        </div>
        <div class="info">
          <ul>
            <li><span>作者：</span><?=$book->author->name?></li>
            <li class="lb"><span>类型：</span>
                <a href="<?=$cat->url?>"><?=$cat->real_name?></a>
            </li>
            <li><span>总点击：</span><font id="cms_clicks"><?=$book->click_count?></font></li>
            <li><span>月点击：</span><font id="cms_mclicks"><?=$book->month_click?></font></li>
            <li class="zdj"><span>周点击：</span><font id="cms_wclicks"><?=$book->week_click?></font></li>
            <li><span>总字数：</span><font id="cms_ready_1"><?=$book->size?></font></li>
            <li><span>创作日期：</span><font id="cms_favorites"><?=$book->created_at?></font></li>
            <li class="wj"><span>状态：</span><?=$book->status?></li>
          </ul>
          <div class="praisesBTN"><a href="javascript:;" title="推荐本书！"><font id="cms_praises"><?=$book->recommend_count?></font> 推荐本书！</a></div>
        </div>
        <div class="words">
            <?php if($book->last_chapter):?>
            最新章节：<a href="<?=$book->last_chapter->url?>"><?=$book->last_chapter->title?></a>
            <?php endif;?>
			 <p>简介：<br/><?=$book->description?></p>
        </div>
        <div class="read_btn">
          <div class="btn2" style="width:328px"><a href="javascript:;" class="sc" title="收藏本书">加入收藏夹</a>
              <a href="<?=$book->download_url?>" class="txt" title="<?=$book->name?>txt下载" target="_blank">TXT 下载</a></div>
        </div>
        <div class="vote"><?=$book->author->name?>的<a href="<?=$cat->url?>"><?=$cat->real_name?></a>作品<?=$book->name?>
            最新章节已经更新，本站提供<a href="<?=$book->url?>" title="<?=$book->name?>最新章节"><?=$book->name?>最新章节</a>全文免费在线阅读，
            <a href="<?=$book->url?>" title="<?=$book->name?>小说"><?=$book->name?>小说</a>全集txt电子书免费下载。如果您发现本站的连载有误，欢迎提交指正！
        </div>
      </div>
    </div>
    <div class="Right">
      <div class="r_box tab">
        <div class="head"> <a class="l active" showBOX="BOX1">同类推荐</a> <a class="r" showBOX="BOX2">作者其他作品</a> </div>
        <div class="box BOX1" style="display:block;">
          <ul class="book-list">
              <?php foreach ($like_book as $key => $item):?>
              <?php if ($key < 1):?>
                      <li>
                      <span class="top-<?=$key?>"><?=$key + 1?></span>     
                      <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a><span>
                              <a href="<?=$item->author->url?>" title="<?=$item->author->name?>作品" target="_blank"><?=$item->author->name?></a></span></li>
                      <li class="first_con"><div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                  <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                          <div class="info"><p><a href="<?=$item->url?>" target="_blank">简介： <?=$item->description?></a></p>
                          </div>
                      </li>
              <?php else: ?>
                      <li>
                      <span class="top-<?=$key?>"><?=$key + 1?></span>     
                      <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                          <span><a href="<?=$item->author->url?>" title="<?=$item->author->name?>作品" target="_blank"><?=$item->author->name?></a></span></li>
                <?php endif;?>
              <?php endforeach;?>
          </ul>
        </div>
        <div class="box BOX2" style="display:none;">
          <ul class="book-list">
              <?php foreach ($author_book as $key => $item):?>
                  <?php if ($key < 1):?>
                      <li>
                      <span class="top-<?=$key?>"><?=$key + 1?></span>     
                      <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a><span>
                              <a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->name?></a></span></li>
                      <li class="first_con"><div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                  <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                          <div class="info"><p><a href="<?=$item->url?>" target="_blank">简介： <?=$item->description?></a></p>
                          </div>
                      </li>
                  <?php else: ?>
                      <li>
                      <span class="top-<?=$key?>"><?=$key + 1?></span>     
                      <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                          <span><a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->name?></a></span></li>
                  <?php endif;?>
              <?php endforeach;?>
        </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clear"></div>
<a id="comment_done"></a>
<div class="box-container no_h">
  <div class="Con jj_pl">
    <div class="Head">
        <h2><?=$book->name?>最新章节列表</h2>
      </div>
      <div class="list_box">
		<ul>
            <?php foreach ($chapter_list as $item) : ?>
                <li><a href="<?=$item->url?>"><?=$item->title?></a></li>
            <?php endforeach;?>
	  </ul>
	</div>
</div>
<div align="left">
<br/><h3>阅读提示：</h3><br/>
1、小说《<?=$book->name?>》所描述的内容只是作者【<?=$book->author->name?>】的个人写作观点，不保证其中情节的真实性，请勿模仿！<br/>
2、《<?=$book->name?>》版权归原作者【<?=$book->author->name?>】所有，本书仅代表作者本人的文学作品观点，仅供娱乐请莫当真。
</div>
</div>

<!--body结束-->
<div class="clear"></div>
<?php $this->extend('layouts/footer');?>