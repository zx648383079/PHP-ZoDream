<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\SEO\Domain\Option;
/** @var $this View */

$icp_beian = Option::value('site_icp_beian');

$this->registerCssFile([
    '@font-awesome.min.css',
    '@dialog.min.css',
    '@zodream.min.css',
    '@auth.min.css'])
    ->registerJsFile([
        '@js.cookie.min.js',
        '@jquery.min.js',
        '@jquery.dialog.min.js',
        '@main.min.js',
        '@base64.min.js',
        '@auth.min.js'])
    ->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>
<!DOCTYPE html>
<html lang="<?=app()->getLocale()?>">
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