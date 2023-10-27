<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\SEO\Domain\Option;
/** @var $this View */

$icp_beian = Option::value('site_icp_beian');

$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@dialog.css')
    ->registerCssFile('@zodream.css')
    ->registerCssFile('@auth.css')
    ->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@main.min.js')
    ->registerJsFile('@base64.min.js')
    ->registerJsFile('@auth.min.js')
    ->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>
<!DOCTYPE html>
<html lang="<?=trans()->getLanguage()?>">
<head>
    <title><?=$this->title?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>
<body class="login-page">
    <?=$this->contents()?>

    <?php if($icp_beian):?>
    <div class="footer">
        <a href="http://www.miitbeian.gov.cn/" target="_blank"><?=$icp_beian?></a>
    </div>
    <?php endif;?>
    <?=$this->footer()?>
</body>
</html>