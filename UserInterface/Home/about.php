<?php
defined('APP_DIR') or exit();
$this->extend(array(
		'layout' => array(
				'head'
		))
);
?>
<div class="content text-center">
		<div class="who_we_are">
				<?php $this->ech('data.0.value');?>
		</div>
		
		<?php $this->extend(array(
            'layout' => array(
                'new'
        )));?>
		</div>

</div>	
<?php
$this->extend(array(
		'layout' => array(
				'foot'
		))
);
?>