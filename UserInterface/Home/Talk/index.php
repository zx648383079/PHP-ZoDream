<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'zodream/talk.css'
    )
);
$data = $this->get('data', array());
?>

<div class="content">
  <div class="wrapper">
    <div class="light"><i></i></div>
    <hr class="line-left">
    <hr class="line-right">
    <div class="talk">
      <h1 class="title"><?php $this->ech('title');?></h1>
      <?php foreach ($data as $value) {?>
         <div class="year">
            <h2><a href="javascript:0;"><?php echo date('Y年', $value[0]['create_at']);?><i></i></a></h2>
            <div class="list">
                <ul>
                    <?php foreach ($value[1] as $item) {?>
                    <li class="cls">
                        <div class="date"><?php echo date('m月d日', $item['create_at']);?></div>
                        <div class="intro"><?php echo $item['content'];?></div>
                    </li>
                    <?php }?>
                </ul>
            </div>
       </div>
    <?php }?>
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
    require(['home/talk']);
</script>
       <?php }
    )
);
?>