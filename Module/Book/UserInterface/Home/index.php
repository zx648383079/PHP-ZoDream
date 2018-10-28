<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->body_class = 'body';
$this->extend('layouts/header');
?>
<div class="clear"></div>
<!--body开始-->
<div class="box-container">
  <div class="left fyb">
    <div class="head">
      <h2>风云榜</h2>
    </div>
    <div class="con">
		<ul class="list">
            <?php foreach ($click_bang as $item):?>
                <li>
                    <div class="div_c">[<a href="<?=$item->category->url?>" target="_blank" class="c" title="<?=$item->category->name?>"><?=$item->category->real_name?></a>]</div>
                    <div class="div_t"><a href="<?=$item->url?>" class="t" title="<?=$item->name?>" target="_blank"><?=$item->name?></a><span>(<?=$item->click_count?>)</span></div>
                </li>
            <?php endforeach;?>
		</ul>
    </div>
    <div class="bot"></div>
  </div>
  <div class="right zxgx">
    <div class="head">
      <h2>封推新书 THE BEST NEWBOOK</h2></div>
    <div class="con">
    <?php if($book):?>
        <div class="u">
            <div class="pic"><a href="<?=$book->url?>" title="<?=$book->name?>" target="_blank">
                    <img class="lazy" src="<?=$book->cover?>" alt="<?=$book->name?>" ></a></div>
            <div class="title">
                <h2><a href="<?=$book->url?>" title="<?=$book->name?>" target="_blank"><?=$book->name?></a></h2>
                <span>作者：<a href="<?=$book->author->url?>" target="_blank" title="<?=$book->author->name?>作品"><?=$book->author->name?></a>&nbsp;&nbsp;类型：
                    [<a href="<?=$book->category->url?>" target="_blank" title="<?=$book->category->name?>"><?=$book->category->name?></a>]</span>
            </div>
            <div class="info">
                <p>最新章节：<a href="<?=$book->last_chapter->url?>" title="<?=$book->last_chapter->title?>" target="_blank"><?=$book->last_chapter->title?></a>
                    <br>
                    <?=$book->last_chapter->description?>
                </p>
            </div>
        </div>
    <?php endif;?>

      <div class="d">
        <div class="n_p_box">
          <input type="button" title="上一页" value="上一页" class="active" />
          <input type="button" title="下一页" value="下一页" />
        </div>
        <div class="con_box">
          <div class="box">
              <?php foreach ($cat_list as $key => $cat):?>
                  <?php if ($key < 3): ?>
                      <div class="lm_li"><h2><a href="<?=$cat->url?>" title="<?=$cat->name?>"><?=$cat->name?></a></h2>
                          <ul>
                              <?php foreach ($cat->recommend_book as $item):?>
                                  <li>·<span>[<a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a>]</span>
                                      <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a></li>
                              <?php endforeach;?>
                          </ul>
                      </div>
                <?php endif;?>
              <?php endforeach;?>
          </div>
		<div class="box" style="display:none;">
            <?php foreach ($cat_list as $key => $cat):?>
                <?php if ($key > 2): ?>
                    <div class="lm_li"><h2><a href="<?=$cat->url?>" title="<?=$cat->name?>"><?=$cat->name?></a></h2>
                        <ul>
                            <?php foreach ($cat->recommend_book as $item):?>
                                <li>·<span>[<a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a>]</span>
                                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
          </div>        
		</div>
      </div>
    </div>
  </div>
</div>
<div class="clear"></div>
<div class="box-container bw">
  <div class="Head">
    <h2>新书推荐榜</h2>
    <span class="j"></span>
	<div class="morelist">
      <div class="more"><a href="<?=$this->url('./search/list')?>" title="查看所有新书" target="_blank">更多新书&#160;>></a></div>
    </div>
  </div>
  <div class="Con">
    <div class="Left">
        <?php foreach ($new_recommend_book as $item):?>
            <div class="bw_box">
                <div class="t"><a href="<?=$item->url?>" target="_blank" title="<?=$item->name?>在线阅读txt下载"><?=$item->name?></a><span>（推荐：<?=$item->recommend_count?>）</span></div>
                <div class="pic"><a href="<?=$item->url?>" target="_blank" title="<?=$item->name?>在线阅读txt下载">
                        <img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>在线阅读txt下载"></a></div>
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
          <h2>本周点击榜</h2>
        </div>
        <ul class="book-list">
            <?php foreach ($week_click_book as $key => $item):?>
                <?php if ($key < 1):?>
                    <li>
                    <span class="top-<?=$key?>"><?=$key + 1?></span>
                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                        <div class="a_l">
                            <div><span>作者:</span><a href="<?=$item->author->url?>" title="<?=$item->author->name?>小说作品" target="_blank"><?=$item->author->name?></a></div>
                            <div><span>类型:</span><a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->real_name?></a></div>
                            <div><span>点/推:</span><?=$item->click_count?>/<?=$item->recommend_count?></div>
                            <div><?=$item->description?></div>
                        </div>
                    </li>
                <?php else: ?>
                    <li>
                    <span class="top-<?=$key?>"><?=$key + 1?></span>    
                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                <?php endif;?>
            <?php endforeach;?>
        </ul></div>
        <div class="r_box qldzb">
				<div class="head">
				  <h2>本周推荐榜</h2>
				</div>
				<ul class="book-list">
                    <?php foreach ($week_recommend_book as $key => $item):?>
                        <?php if ($key < 1):?>
                            <li>
                            <span class="top-<?=$key?>"><?=$key + 1?></span>       
                            <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                                <span><?=$item->click_count?></span></li>
                            <li class="first_con">
                                <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                        <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                                <div class="a_l">
                                    <div><span>作者:</span><a href="<?=$item->author->url?>" title="<?=$item->author->name?>小说作品" target="_blank"><?=$item->author->name?></a></div>
                                    <div><span>类型:</span><a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->real_name?></a></div>
                                    <div><span>点/推:</span><?=$item->click_count?>/<?=$item->recommend_count?></div>
                                    <div><?=$item->description?></div>
                                </div>
                            </li>
                        <?php else: ?>
                            <li>
                                <span class="top-<?=$key?>"><?=$key + 1?></span>   
                                <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                                <span><?=$item->click_count?></span></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul></div>
    </div>
  </div>
</div>
<div class="clear"></div>
<div class="box-container jp">
  <div class="Head">
    <h2>精品推荐</h2>
    <span>Boutique Recommend</span> <span class="j"></span>
    <div class="morelist">
      <div class="more"><a href="<?=$this->url('./search/list')?>" title="更多精品小说" target="_blank">更多精品小说&#160;>></a></div>
    </div>
  </div>
  <div class="Con jp">
    <div class="Left">
        <div class="l_con">
        <div class="pic">
          <ul>
              <?php foreach ($best_recommend_book as $key => $item):?>
              <?php if ($key < 6): ?>
                  <li><a href="<?=$item->url?>" class="p" title="类型：<?=$item->category->name?>，作者：<?=$item->author->name?>，总点击：<?=$item->click_count?>" target="_blank">
                          <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="" > </a>
                      <a href="<?=$item->url?>" class="t" title="类型：<?=$item->category->name?>，作者：<?=$item->author->name?>，总点击：<?=$item->click_count?>" target="_blank"><?=$item->name?></a></li>
                  <?php endif;?>
              <?php endforeach;?>
          </ul>
        </div>
        <div class="lm">
            <?php foreach ($cat_list as $key => $cat):?>
                <?php if ($key < 3): ?>
                    <div class="lm_li"><h3><a href="<?=$cat->url?>" title="<?=$cat->name?>"><?=$cat->name?></a></h3>
                        <ul>
                            <?php foreach ($cat->best_recommend_book as $item):?>
                                <li>·<span>[<a href="" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a>]</span>
                                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
	  <div class="pic">
          <ul>
              <?php foreach ($best_recommend_book as $key => $item):?>
                <?php if ($key > 5): ?>
                  <li><a href="<?=$item->url?>" class="p" title="类型：<?=$item->category->name?>，作者：<?=$item->author->name?>，总点击：<?=$item->click_count?>" target="_blank">
                          <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="" > </a>
                      <a href="<?=$item->url?>" class="t" title="类型：<?=$item->category->name?>，作者：<?=$item->author->name?>，总点击：<?=$item->click_count?>" target="_blank"><?=$item->name?></a></li>
                  <?php endif;?>
              <?php endforeach;?>
          </ul>
        </div>
        <div class="lm">
            <?php foreach ($cat_list as $key => $cat):?>
                <?php if ($key > 2): ?>
                    <div class="lm_li"><h3><a href="<?=$cat->url?>" title="<?=$cat->name?>"><?=$cat->name?></a></h3>
                        <ul>
                            <?php foreach ($cat->best_recommend_book as $item):?>
                                <li>·<span>[<a href="" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a>]</span>
                                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
      </div>
    </div>
    <div class="Right">
      <div class="r_box yddj">
        <div class="head">
          <h2>月度点击榜</h2>
        </div>
        <ul class="book-list">
            <?php foreach ($month_click_book as $key => $item):?>
                <?php if ($key < 1):?>
                    <li>
                    <span class="top-<?=$key?>"><?=$key + 1?></span>       
                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                        <div class="a_l">
                            <div><span>作者:</span><a href="#" title="<?=$item->author->name?>小说作品" target="_blank"><?=$item->author->name?></a></div>
                            <div><span>类型:</span><a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->real_name?></a></div>
                            <div><span>点/推:</span><?=$item->click_count?>/<?=$item->recommend_count?></div>
                            <div><?=$item->description?></div>
                        </div>
                    </li>
                <?php else: ?>
                    <li>
                    <span class="top-<?=$key?>"><?=$key + 1?></span>       
                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                <?php endif;?>
            <?php endforeach;?>
        </ul>
      </div>
      <div class="r_box ydhp">
        <div class="head">
          <h2>月度推荐榜</h2>
        </div>
        <ul class="book-list">
            <?php foreach ($month_recommend_book as $key => $item):?>
                <?php if ($key < 1):?>
                    <li>
                    <span class="top-<?=$key?>"><?=$key + 1?></span>       
                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                        <div class="a_l">
                            <div><span>作者:</span><a href="<?=$item->author->url?>" title="<?=$item->author->name?>小说作品" target="_blank"><?=$item->author->name?></a></div>
                            <div><span>类型:</span><a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->real_name?></a></div>
                            <div><span>点/推:</span><?=$item->click_count?>/<?=$item->recommend_count?></div>
                            <div><?=$item->description?></div>
                        </div>
                    </li>
                <?php else: ?>
                    <li>
                    <span class="top-<?=$key?>"><?=$key + 1?></span>       
                    <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                <?php endif;?>
            <?php endforeach;?>
		</ul>
      </div>      
    </div>
  </div>
</div>
<div class="clear"></div>
<div class="box-container ph">
  <div class="Head">
    <h2>排行榜</h2>
    <span>Ranking</span> <span class="j"></span>
    <div class="morelist">
      <div class="more"><a href="<?=$this->url('./search/top')?>" title="更多排行榜" target="_blank">更多排行榜&#160;>></a></div>
    </div>
  </div>
  <div class="Con ph">
    <div class="p_box dj">
      <div class="head">
        <h2>总点击榜</h2>
      </div>
      <div class="ul_h"> <span class="p">排序</span> <span class="s">类型<em>&#160;/&#160;</em>书名</span> <span class="d">点击数</span> </div>
      <ul class="book-list">
          <?php foreach ($click_bang as $key => $item):?>
              <li>
                <span class="top-<?=$key?>"><?=$key + 1?></span>
                [<a class="r" href="<?=$item->category->url?>" title="<?=$item->category->name?>小说" target="_blank"><?=$item->category->real_name?></a>]&nbsp;
                  <a href="<?=$item->url?>" class="t" title="<?=$item->name?>作者：<?=$item->author->name?>" target="_blank"><?=$item->name?></a><span>(<?=$item->click_count?>)</span></li>
          <?php endforeach;?>
      </ul>
    </div>
    <div class="p_box hp">
      <div class="head">
        <h2>总字数榜</h2>
      </div>
      <div class="ul_h"> <span class="p">排序</span> <span class="s">类型<em>&#160;/&#160;</em>书名</span> <span class="d">总字数</span> </div>
      <ul class="book-list">
          <?php foreach ($size_bang as $key => $item):?>
              <li>
              <span class="top-<?=$key?>"><?=$key + 1?></span>
              [<a class="r" href="<?=$item->category->url?>" title="<?=$item->category->name?>小说" target="_blank"><?=$item->category->real_name?></a>]&nbsp;
                  <a href="<?=$item->url?>" class="t" title="<?=$item->name?>作者：<?=$item->author->name?>" target="_blank"><?=$item->name?></a><span>(<?=$item->format_size?>)</span></li>
          <?php endforeach;?>
      </ul>
    </div>
    <div class="p_box pl">
      <div class="head">
        <h2>总推荐榜</h2>
      </div>
      <div class="ul_h"> <span class="p">排序</span> <span class="s">类型<em>&#160;/&#160;</em>书名</span> <span class="d">推荐数</span> </div>
      <ul class="book-list">
          <?php foreach ($recommend_bang as $key => $item):?>
              <li>
              <span class="top-<?=$key?>"><?=$key + 1?></span>
              [<a class="r" href="<?=$item->category->url?>" title="<?=$item->category->name?>小说" target="_blank"><?=$item->category->real_name?></a>]&nbsp;
                  <a href="<?=$item->url?>" class="t" title="<?=$item->name?>作者：<?=$item->author->name?>" target="_blank"><?=$item->name?></a><span>(<?=$item->recommend_count?>)</span></li>
          <?php endforeach;?>
      </ul>
    </div>
  </div>
</div>
<div class="clear"></div>
<div class="box-container m_list">
  <div class="Head">
    <h2>更新列表</h2>
    <span>New List</span> <span class="j"></span>
  </div>
  <div class="Con">
    <div class="Left">
      <div class="m_head"><span class="t">书名/章节</span> <span class="w">总字数</span> <span class="a">作者</span><span class="z">状态</span> <span class="tm">更新时间</span> </div>
      <ul class="ul_m_list">
          <?php foreach ($book_list as $key => $item):?>
              <li>
                  <div class="title">
                      <div class="t">
                          <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                      </div>
                      <div class="n">
                          [<a href="<?=$item->download_url?>" title="<?=$item->name?>txt下载" target="_blank">下载</a>]
                          <a href="<?=$item->last_chapter->url?>" title="<?=$item->last_chapter->title?>" target="_blank"><?=$item->last_chapter->title?></a>
                      </div>
                  </div>
                  <div class="words"><?=$item->format_size?></div>
                  <div class="author">
                      <a href="#" title="<?=$item->author->name?>作品" target="_blank"><?=$item->author->name?></a>
                  </div><div class="abover"><span><?=$item->status?></span>
                  </div><div class="time"><?=$item->last_at?></div></li>
          <?php endforeach;?>
      </ul>
    </div>
    <div class="Right">
		<div class="r_box cn">
			<div class="head"><h2>新书作家推荐</h2></div>
			<ul>
                <?php foreach ($hot_author as $key => $item):?>
                    <?php if ($key < 1):?>
                        <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                            <span><?=$item->book_count?>/<?=$item->size?></span></li>
                        <li class="first_con">
                            <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                    <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
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
		</div><div class="r_box cmztj cn">
        <div class="head"><h2>热门新书推荐</h2></div>
        <ul>
            <?php foreach ($new_book as $key => $item):?>
                <?php if ($key < 1):?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                        <div class="a_l">
                            <div><span>作者:</span><a href="<?=$item->author->url?>" title="<?=$item->author->name?>小说作品" target="_blank"><?=$item->author->name?></a></div>
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
                                <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                        <div class="a_l">
                            <div><span>作者:</span><a href="<?=$item->author->url?>" title="<?=$item->author->name?>小说作品" target="_blank"><?=$item->author->name?></a></div>
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
    </div>
  </div>
</div>
<!--body结束-->
<div class="clear"></div>
<!--footer开始-->

<?php $this->extend('layouts/footer');?>
