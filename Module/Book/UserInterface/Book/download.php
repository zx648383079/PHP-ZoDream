<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Disk;
/** @var $this View */
$this->title = 'ZoDream';
$this->extend('layouts/header');
?>
<div class="clear"></div>
<!--body开始-->
<div class="box-container local">当前位置：<a href="<?=$this->url('./')?>" title=""><?=$site_name?></a>
    > <a href="<?=$cat->url?>"><?=$cat->real_name?>小说下载</a> >  
    <a href="<?=$book->download_url?>" title="<?=$book->name?>txt下载"><?=$book->name?>txt下载</a></div>
<div class="clear"></div>
<div class="box-container no_h">
  <div class="Con jj">
    <div class="Left">
      <div class="p_box">
          <div class="pic"><a href="<?=$book->url?>" title="<?=$book->name?>小说"><img class="lazy" src="<?=$book->cover?>" alt="<?=$book->name?>小说" /></a></div>
        <div class="rmxx_box">
          <h2>其他热门小说下载</h2>
          <div class="a_box HOT_BOX">
              <?php foreach ($hot_book as $item) : ?>
                  <li><a href="<?=$item->url?>"><?=$item->name?></a></li>
              <?php endforeach;?>
                      
          </div>
        </div>
      </div>
      <div class="j_box">
        <div class="title">
          <h2><?=$book->name?>txt下载</h2>
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
            最新章节：<a href="<?=$book->last_chapter->url?>"><?=$book->last_chapter->title?></a>（<?=$book->last_chapter->created_at?>）
            <?php endif;?>
			 <p><?=$book->author->name?>的<a href="<?=$book->category->url?>" target='_blank' title="<?=$book->category->real_name?>小说" >
                     <?=$book->category->real_name?>小说</a>作品《<a href="<?=$book->url?>" title="<?=$book->name?>"><?=$book->name?></a>》最新章节已经更新，作者<?=$book->author->name?>在这本作品上倾注了非常多的精力和时间，本站提供
                 <a href="<?=$book->url?>" title="<?=$book->name?>txt下载"><?=$book->name?>txt下载</a>，如果您喜欢这本作品，可以在这里免费下载。<br/>声明：<br/>1、请勿用于商业用途，否则后果非常严重，本站无力承担。<br/>
						2、下载后请尽快删除，好公民应该支持正版阅读。</p>
        </div>
        <div class="read_btn">
          <div class="btn2" style="width:108px"><a href="<?=$book->url?>" class="yd" title="在线阅读">在线阅读</a></div>
		  <div class="down">
		  txt文件：<a href="<?=$this->url('./book/txt', ['id' => $book->id])?>" title="<?=$book->name?>txt电子书" target="_blank" onclick="_czc.push(['_trackEvent', '小说下载', 'txt', '<?=$book->name?>','','']);">
                  点击下载(<?=Disk::size($book->size)?>)</a> | zip压缩包：<a href="<?=$this->url('./book/zip', ['id' => $book->id])?>" title="<?=$book->name?>txt电子书zip压缩包" target="_blank" onclick="_czc.push(['_trackEvent', '小说下载', 'zip', '<?=$book->name?>','','']);">
                  点击下载(<?=Disk::size($book->size / 3)?>)</a>
		  </div>
        </div>
        <div class="vote">说明：zip压缩包解压后可得到txt文件，txt文件比较大，请不要用记事本直接打开，除非你的电脑配置够好，否者会卡机或者假死。推荐用专业的文档编辑工具打开（如：Notepad++等），会省很多事。
        </div>
      </div>
    </div>
    <div class="Right">
      <div class="r_box tab">
        <div class="head"> <a class="l active" showBOX="BOX1">同类推荐</a> <a class="r" showBOX="BOX2">作者其他作品</a> </div>
        <div class="box BOX1" style="display:block;">
          <ul>
              <?php foreach ($like_book as $key => $item):?>
                  <?php if ($key < 1):?>
                      <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a><span>
                              <a href="<?=$item->author->url?>" title="<?=$item->author->name?>作品" target="_blank"><?=$item->author->name?></a></span></li>
                      <li class="first_con"><div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                  <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                          <div class="info"><p><a href="<?=$item->url?>" target="_blank">简介： <?=$item->description?></a></p>
                          </div>
                      </li>
                  <?php else: ?>
                      <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                          <span><a href="<?=$item->author->url?>" title="<?=$item->author->name?>作品" target="_blank"><?=$item->author->name?></a></span></li>
                  <?php endif;?>
              <?php endforeach;?>
          </ul>
        </div>
        <div class="box BOX2" style="display:none;">
          <ul><?php foreach ($author_book as $key => $item):?>
                  <?php if ($key < 1):?>
                      <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a><span>
                              <a href="<?=$item->category->url?>" title="<?=$item->category->real_name?>小说" target="_blank"><?=$item->category->name?></a></span></li>
                      <li class="first_con"><div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                  <img class="lazy" src="/assets/images/book_default.jpg" data-original="<?=$item->cover?>" alt="<?=$item->name?>" ></a></div>
                          <div class="info"><p><a href="<?=$item->url?>" target="_blank">简介： <?=$item->description?></a></p>
                          </div>
                      </li>
                  <?php else: ?>
                      <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
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
<div class="box-container no_h">
<div align="left">
<br/><h3>阅读提示：</h3><br/>
1、小说《<a href="<?=$book->url?>" title="<?=$book->name?>"><?=$book->name?></a>》所描述的内容只是作者【<?=$book->author->name?>】的个人写作观点，不保证其中情节的真实性，请勿模仿！<br/>
2、《<a href="<?=$book->url?>" title="<?=$book->name?>"><?=$book->name?></a>》版权归原作者【<?=$book->author->name?>】所有，本书仅代表作者本人的文学作品观点，仅供娱乐请莫当真。
</div>
</div>

<!--body结束-->
<div class="clear"></div>
<?php $this->extend('layouts/footer');?>