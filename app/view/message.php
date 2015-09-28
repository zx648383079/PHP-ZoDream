<?php 
App::extend(array(
	'~layout'=>array(
		'head',
		'nav',
		'menu'
		)
	)
);
?>

<div class="long">
  <ul class="listbox">
    <li>
      <div class="long">
        <h1>哈哈</h1>
      </div>
      <div class="short">
        <a href="#">编辑</a>
        <a href="#">删除</a>
      </div>
    </li>
    <li>
      <div class="long">
        <h1>哈哈</h1>
      </div>
      <div class="short">
        <a href="#">编辑</a>
        <a href="#">删除</a>
      </div>
    </li>
  </ul>
  
  <div class="pager">
    <a href="#">上一页</a>
    <a href="#">1</a>
    <span>2</span>
    <a href="#">3</a>
    <a href="#">4</a>
  <a href="#">下一页</a>
</div>
</div>



</div>
	
	
<?php App::extend('~layout.foot');?>