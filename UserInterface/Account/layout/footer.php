<?php
defined('APP_DIR') or exit();
use Module\SEO\Domain\Option;
/** @var $this \Zodream\Template\View */

$icp_beian = Option::value('site_icp_beian');
?>
    <?php if($icp_beian):?>
    <div class="footer">
        <a href="http://www.miitbeian.gov.cn/" target="_blank"><?=$icp_beian?></a>
    </div>
    <?php endif;?>
    <script type="text/javascript" src="/assets/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
    <?=$this->footer()?>
    </body>
</html>
