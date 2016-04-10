<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    )), array(
        'zodream/talk.css'
    )
);
?>

<div class="content">
  <div class="wrapper">
    <div class="light"><i></i></div>
    <hr class="line-left">
    <hr class="line-right">
    <div class="talk">
      <h1 class="title"><?php $this->ech('title');?></h1>
      <?php
      $data = $this->get('data', array());
      $year = null;
      foreach ($data as $item) {
        $newyear = date('Y年', $item['create_at']);
        if ($year !== $newyear) {
          $year = $newyear;
        ?>
          </ul>
        </div>
       </div>
      <div class="year">
        <h2><a href="javascript:0;"><?php echo $year;?><i></i></a></h2>
        <div class="list">
          <ul>
        <?php }
        ?>
      <li class="cls">
        <div class="date"><?php echo date('m月d日', $item['create_at']);?></div>
        <div class="intro"><?php echo $item['content'];?></div>
      </li>
        <?php
      }
        if (!empty($data)) {?>
          </ul>
        </div>
      </div>
     <?php }
      ?>
    </div>
  </div>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        function() {?>
            <script>
                require(['empire/talk']);
            </script>
       <?php }
    )
);
?>