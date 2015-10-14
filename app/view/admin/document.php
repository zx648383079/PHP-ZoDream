<?php 

App::$data['menu'] = 2;
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
  <div class="editbox">
    <div class="tool">
      <a href="">新增</a>
      <a href="">编辑</a>
      <a href="">删除</a>
      <a href="">上移</a>
      <a href="">下移</a>
    </div>
    <ul>
      <li>aaa</li>
      <li>aaa</li>
      <li>aaa
        <span class="more">+</span>
        <ul>
          <li>bb</li>
          <li>bb</li>
          <li>bb
            <span class="more">+</span>
            <ul>
              <li>bb</li>
              <li>bb</li>
              <li>bb</li>
              <li>bb</li>
            </ul>
          </li>
          <li>bb</li>
        </ul>
      </li>
      <li>aaa</li>
    </ul>
  </div>
  <?php
    $data = App::ret('data');
    if(!empty($data)) 
    {
      foreach ($data as $value) {
        echo $value['page'];
      }
    }
  ?>
</div>
</div>
	
	
<?php App::extend('~layout.foot');?>