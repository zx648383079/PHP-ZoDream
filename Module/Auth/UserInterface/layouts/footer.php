<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\SEO\Domain\Option;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@login.min.js');

$icp_beian = Option::value('site_icp_beian');
?>
<?php if($icp_beian):?>
<div class="footer">
    <a href="http://www.miitbeian.gov.cn/" target="_blank"><?=$icp_beian?></a>
</div>
<?php endif;?>
<?=$this->footer()?>
</body>
</html>
