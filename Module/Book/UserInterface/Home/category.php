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
<!--header开始-->
<?php $this->extend('./head', ['nav_index' => $cat->id]) ?>
<!--header结束--> 

<div class="clear"></div>
<!--body开始-->
<div class="Layout local">当前位置：
    <a href="<?=$this->url('./')?>" title=""><?=$site_name?></a> >
    <a href="<?=$cat->url?>"><?=$cat->real_name?>小说</a>
</div>
<div class="Layout no_h">
  <div class="Con lm_new">
    <div class="Left">
      <div class="h_pic_box">
          <div class="pic" id="h_pic">
              <?php foreach ($cat_book as $key => $item):?>
            <div id="pic<?=$key?>" onmouseout="mmplay()" onmouseover="mmstop()">
              <a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                  <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" width="210" height="280" /></a>
              <span><a href="<?=$item->url?>" title="<?=$item->name?>-<?=$item->author->name?>作品" target="_blank"><?=$item->name?></a>
              </span></div>
              <?php endforeach;?>
        </div>
      </div>
      <script type="text/javascript">
	  document.getElementById("tabpic0").className="active";
	  document.getElementById("pic0").style.display="block";
	  var cateimgs=document.getElementById("h_pic").getElementsByTagName("div");
      var a=0;
      var b=cateimgs.length;
      var time=3000;
      function showpic(id)
      {
          a=id;
          clearTimeout(mytime);
          for (i=0;i<b;i++)
          {
              document.getElementById("tabpic"+i).className="";
              document.getElementById("pic"+i).style.display="none";
              document.getElementById("word"+i).style.display="none";
          }
          document.getElementById("tabpic"+id).className="active";
          document.getElementById("pic"+id).style.display="block";
          document.getElementById("word"+id).style.display="block";
          mytime=setTimeout("showpic("+(id==(b-1)? 0:id+1)+")", time);
      }
      mytime=setTimeout("showpic(1)", time);
      function mmstop(){clearTimeout(mytime);}
      function mmplay(){mytime=setTimeout("showpic("+a+")", time);}
      </script>
      <div class="new_box">
        <div class="u">
          <div class="head">
            <h2>最新<?=$cat->real_name?>小说</h2>
            <span class="j"></span> </div>
          <div class="con">
              <div class="con">
                  <h2><a href="<?=$book->url?>" title="<?=$book->name?>" target="_blank"><?=$book->name?></a><span> 作者：
                          <a href="<?=$book->author->url?>" target="_blank" title="<?=$book->author->name?>作品"><?=$book->author->name?></a></span></h2>
                  <p>
                      <?=$book->description?>
                  </p>
              </div>
          </div>
        </div>
        <div class="d">
          <div class="head">
            <h2>精品<?=$cat->real_name?>小说推荐</h2>
            <span class="j"></span> </div>
          <div class="con">
            <ul>
                <?php foreach ($hot_book as $item):?>
                    <li>·[<a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><span><?=$item->author->name?></span>
                        </a>]<a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a></li>
                <?php endforeach;?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="Right">
      <div class="r_box tab">
        <div class="head"> <a class="l active" showBOX="BOX1">月推荐榜</a> <a class="r" showBOX="BOX2">月排行榜</a> </div>
        <div class="box BOX1" style="display:block;">
			<ul>
                <?php foreach ($recommend_bang as $key => $item):?>
                    <?php if ($key < 1):?>
                        <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                            <span><?=$item->click_count?></span></li>
                        <li class="first_con">
                            <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                    <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" style="display: inline; background: transparent url(&quot;/images/loading.gif&quot;) no-repeat scroll center center;"></a></div>
                            <div class="a_l"><div class="a">
                                    <span>作者:</span>
                                    <a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a>
                                </div><div class="l">
                                    <span>下载:</span><a href="<?=$item->download_url?>" target="_blank" title="<?=$item->name?>txt下载">txt下载</a></div></div>
                            <div class="info"><p><a href="<?=$item->url?>" target="_blank"><?=$item->description?></a></p></div>
                        </li>

                    <?php else: ?>
                        <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                            <span><?=$item->click_count?></span></li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="box BOX2" style="display:none;">
			<ul>
                <?php foreach ($click_bang as $key => $item):?>
                    <?php if ($key < 1):?>
                        <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                            <span><?=$item->click_count?></span></li>
                        <li class="first_con">
                            <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                    <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" style="display: inline; background: transparent url(&quot;/images/loading.gif&quot;) no-repeat scroll center center;"></a></div>
                            <div class="a_l"><div class="a">
                                    <span>作者:</span>
                                    <a href="<?=$item->author->url?>" target="_blank" title="<?=$item->author->name?>作品"><?=$item->author->name?></a>
                                </div><div class="l">
                                    <span>下载:</span><a href="<?=$item->download_url?>" target="_blank" title="<?=$item->name?>txt下载">txt下载</a></div></div>
                            <div class="info"><p><a href="<?=$item->url?>" target="_blank"><?=$item->description?></a></p></div>
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
</div>
<div class="clear"></div>
<div class="Layout m_list">
  <div class="Head">
    <h2>更新列表</h2>
    <span>New List</span> <span class="j"></span>
	<div class="morelist">
      <div class="more"><a href="{dede:field name='typedir'/}/shuku.html" style="font-weight: 800; text-decoration:underline" title="查看更多<?=$cat->real_name?>小说">更多<?=$cat->real_name?>小说&nbsp;&gt;&gt;</a></div>
    </div>
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
                  <div class="words"><?=$item->size?></div>
                  <div class="author">
                      <a href="<?=$item->author->url?>" title="<?=$item->author->name?>作品" target="_blank"><?=$item->author->name?></a>
                  </div><div class="abover"><span><?=$item->status?></span>
                  </div><div class="time"><?=$item->updated_at?></div></li>
          <?php endforeach;?>
         </ul>
		 <div class="bot_more"><a href="{dede:field name='typedir'/}/shuku.html" title="查看更多<?=$cat->real_name?>小说">更多<?=$cat->real_name?>小说&nbsp;&gt;&gt;</a></div>
    </div>
    <div class="Right">
		<div class="r_box cn">
			<div class="head"><h2><?=$cat->real_name?>小说作家推荐</h2></div>
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
		</div><div class="r_box cmztj cn">
        <div class="head"><h2><?=$cat->real_name?>新书推荐</h2></div>
        <ul>
            <?php foreach ($new_book as $key => $item):?>
                <?php if ($key < 1):?>
                    <li><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank"><?=$item->name?></a>
                        <span><?=$item->click_count?></span></li>
                    <li class="first_con">
                        <div class="pic"><a href="<?=$item->url?>" title="<?=$item->name?>" target="_blank">
                                <img class="lazy" src="<?=$item->cover?>" alt="<?=$item->name?>" style="display: inline; background: transparent url(&quot;/images/loading.gif&quot;) no-repeat scroll center center;"></a></div>
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
          <h2>完本<?=$cat->real_name?>小说推荐</h2>
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
<?php $this->extend('./footer3')?>
<?=$this->footer()?>

</body>
</html>
