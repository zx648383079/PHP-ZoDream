<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$this->body_class = 'bodyph';
$this->extend('layouts/header', ['nav_index' => 98]);
?>

<div class="clear"></div>
<div class="box-container list">
  <div class="Head">
    <h2>排行榜</h2>
    <span>Ranking</span> <span class="j"></span>
    <div class="morelist">
      <div class="more">排行榜时时更新，为书友们提供最新的小说排行信息！</div>
    </div>
  </div>
  <div class="Con">
    <div class="Left">
      <div class="topList">
        <div class="tit">
          <h3>全网小说点击榜</h3>
          <ul id="tab_box_01">
            <li >总</li>
            <li class="Li_Mover">月</li>
            <li>周</li>
          </ul>
        </div>
        <div class="con" id="box_01_zong">
          <ul class="book-list">
              <?php foreach ($click_bang as $key => $item): ?>
                  <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                  <span class="top-<?=$key?>"><?=$key + 1?></span> 
                  <dl class="dl_01">
                          <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                          <dd>
                              <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                              <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                              <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                              <p>字数：<?=$item->format_size?></p>
                              <p>总点击：<?=$item->click_count?></p>
                              <p>总推荐：<?=$item->recommend_count?></p>
                          </dd>
                      </dl>
                  </li>
              <?php endforeach;?>
          </ul>
		  <div class="more">查看更多……</div>
		</div>
        <div class="con Li_Mover" id="box_01_yue">
          <ul class="book-list">
              <?php foreach ($month_click_bang as $key => $item): ?>
                  <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                  <span class="top-<?=$key?>"><?=$key + 1?></span> 
                  <dl class="dl_01">
                          <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                          <dd>
                              <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                              <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                              <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                              <p>字数：<?=$item->format_size?></p>
                              <p>总点击：<?=$item->click_count?></p>
                              <p>总推荐：<?=$item->recommend_count?></p>
                              
                          </dd>
                      </dl>
                  </li>
              <?php endforeach;?>
          </ul>
		  <div class="more">查看更多……</div>
		</div>
        <div class="con" id="box_01_zhou">
          <ul class="book-list">
              <?php foreach ($week_click_bang as $key => $item): ?>
                  <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                  <span class="top-<?=$key?>"><?=$key + 1?></span> 
                  <dl class="dl_01">
                          <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                          <dd>
                              <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                              <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                              <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                              <p>字数：<?=$item->format_size?></p>
                              <p>总点击：<?=$item->click_count?></p>
                              <p>总推荐：<?=$item->recommend_count?></p>
                              
                          </dd>
                      </dl>
                  </li>
              <?php endforeach;?>
          </ul>
		  <div class="more">查看更多……</div>
		</div>
      </div>
      <div class="topList">
        <div class="tit">
          <h3>全网小说推荐榜</h3>
          <ul id="tab_box_02">
            <li>总</li>
            <li class="Li_Mover">月</li>
            <li >周</li>
          </ul>
        </div>
        <div class="con" id="box_02_zong">
          <ul class="book-list">
              <?php foreach ($recommend_bang as $key => $item): ?>
                  <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                  <span class="top-<?=$key?>"><?=$key + 1?></span> 
                  <dl class="dl_01">
                          <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                          <dd>
                              <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                              <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                              <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                              <p>字数：<?=$item->format_size?></p>
                              <p>总点击：<?=$item->click_count?></p>
                              <p>总推荐：<?=$item->recommend_count?></p>
                              
                          </dd>
                      </dl>
                  </li>
              <?php endforeach;?>
          </ul>
		  <div class="more">查看更多……</div>
		</div>
        <div class="con Li_Mover" id="box_02_yue">
          <ul class="book-list">
              <?php foreach ($month_recommend_bang as $key => $item): ?>
                  <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                  <span class="top-<?=$key?>"><?=$key + 1?></span> 
                  <dl class="dl_01">
                          <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                          <dd>
                              <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                              <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                              <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                              <p>字数：<?=$item->format_size?></p>
                              <p>总点击：<?=$item->click_count?></p>
                              <p>总推荐：<?=$item->recommend_count?></p>
                              
                          </dd>
                      </dl>
                  </li>
              <?php endforeach;?>
          </ul>
		  <div class="more">查看更多……</div>
		</div>
        <div class="con" id="box_02_zhou">
          <ul class="book-list">
              <?php foreach ($week_recommend_bang as $key => $item): ?>
                  <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                  <span class="top-<?=$key?>"><?=$key + 1?></span> 
                  <dl class="dl_01">
                          <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                          <dd>
                              <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                              <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                              <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                              <p>字数：<?=$item->format_size?></p>
                              <p>总点击：<?=$item->click_count?></p>
                              <p>总推荐：<?=$item->recommend_count?></p>
                              <p class="sc">[<a target="_blank" href="#" title="<?=$item->name?>txt下载"><?=$item->name?>txt下载</a>]</p>
                          </dd>
                      </dl>
                  </li>
              <?php endforeach;?>
          </ul>
		  <div class="more">查看更多……</div>
		</div>
      </div>
	  <div class="topList">
        <div class="tit">
          <h3>全网小说字数榜</h3>
        </div>
        <div class="con Li_Mover" id="box_03_zong">
          <ul class="book-list">
              <?php foreach ($size_bang as $key => $item): ?>
                  <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                  <span class="top-<?=$key?>"><?=$key + 1?></span> 
                  <dl class="dl_01">
                          <dt><em><?=$item->format_size?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                          <dd>
                              <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                              <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                              <p>作者：<span><a href="#" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                              <p>字数：<?=$item->format_size?></p>
                              <p>总点击：<?=$item->click_count?></p>
                              <p>总推荐：<?=$item->recommend_count?></p>
                              
                          </dd>
                      </dl>
                  </li>
              <?php endforeach;?>
          </ul>
		  <div class="more">查看更多……</div>
		</div>
      </div>
		<?php foreach ($cat_list as $cat):?>
        <div class="topList">
			<div class="tit">
			  <h3><?=$cat->real_name?>小说排行榜</h3>
			  <ul id="tab_box_0">
				<li onmouseover="Li_Mover(this, <?=$cat->id?>, 'zong','tab')">总</li>
				<li class="Li_Mover" onmouseover="Li_Mover(this,<?=$cat->id?>, 'yue','tab')">月</li>
				<li onmouseover="Li_Mover(this, <?=$cat->id?>,'zhou','tab')">周</li>
			  </ul>
			</div>
			<div class="con" id="box_0<?=$cat->id?>_zong">
			  <ul class="book-list">
                  <?php foreach ($cat->book_list as $key => $item): ?>
                      <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                      <span class="top-<?=$key?>"><?=$key + 1?></span>   
                      <dl class="dl_01">
                              <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                              <dd>
                                  <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                                  <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                                  <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                                  <p>字数：<?=$item->format_size?></p>
                                  <p>总点击：<?=$item->click_count?></p>
                                  <p>总推荐：<?=$item->recommend_count?></p>
                                  
                              </dd>
                          </dl>
                      </li>
                  <?php endforeach;?>
              </ul><div class="more">查看更多……</div>
			</div>
			<div class="con Li_Mover" >
			  <ul class="book-list">
                  <?php foreach ($cat->month_book as $key => $item): ?>
                      <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                      <span class="top-<?=$key?>"><?=$key + 1?></span>   
                      <dl class="dl_01">
                              <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                              <dd>
                                  <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                                  <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                                  <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                                  <p>字数：<?=$item->format_size?></p>
                                  <p>总点击：<?=$item->click_count?></p>
                                  <p>总推荐：<?=$item->recommend_count?></p>
                                  
                              </dd>
                          </dl>
                      </li>
                  <?php endforeach;?>
              </ul>
                <div class="more">查看更多……</div>
			</div>
			<div class="con" id="box_0'.$m.'_zhou">
			  <ul class="book-list">
                  <?php foreach ($cat->week_book as $key => $item): ?>
                      <li <?= $key < 1 ? 'class="Li_Mover"' : '' ?> >
                      <span class="top-<?=$key?>"><?=$key + 1?></span> 
                      <dl class="dl_01">
                              <dt><em><?=$item->click_count?></em><a target="_blank" href="<?=$item->url?>"><?=$item->name?></a></dt>
                              <dd>
                                  <div class="img"><a target="_blank" href="<?=$item->url?>"><img src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>"></a></div>
                                  <strong>类型：[<a href="<?=$item->category->url?>" target="_blank" title="<?=$item->category->real_name?>小说"><?=$item->category->real_name?></a>]</strong>
                                  <p>作者：<span><a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a></span></p>
                                  <p>字数：<?=$item->format_size?></p>
                                  <p>总点击：<?=$item->click_count?></p>
                                  <p>总推荐：<?=$item->recommend_count?></p>
                                  
                              </dd>
                          </dl>
                      </li>
                  <?php endforeach;?>
              </ul><div class="more">查看更多……</div>
		</div>
      </div>
	    <?php endforeach;?>
     </div>
    <div class="Right">
		<div class="r_box cn">
			<div class="head"><h2>小说作家推荐</h2></div>
			<ul>
                <?php foreach ($hot_author as $key => $item):?>
                    <?php if ($key < 1):?>
                        <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                            <span><?=$item->book_count?>/<?=$item->format_size?></span></li>
                        <li class="first_con">
                            <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                    <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                            <div class="a_l">
                                <div><span>作品数:</span><?=$item->book_count?></div>
                                <div><span>总字数:</span><?=$item->format_size?></div>
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
      <div class="r_box ad ad200">
      </div>
    </div>
  </div>
</div>
<div class="clear"></div>
<!--footer开始-->

<?php $this->extend('layouts/footer');?>