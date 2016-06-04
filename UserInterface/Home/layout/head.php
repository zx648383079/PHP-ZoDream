<?php
/** @var $this \Zodream\Domain\Response\View */
?>
<!DOCTYPE HTML>
<html lang="ch-ZHS">
<head>
<title><?php $this->ech('title', 'ZoDream');?>-<?php $this->ech('tagline', 'ZoDream');?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php $this->ech('keywords');?>" />
<meta name="Description" content="<?php $this->ech('description');?>" />
<meta name="author" content="<?php $this->ech('author');?>" />
<link rel="icon" href="<?php $this->asset('images/favicon.png');?>">
<?php $this->jcs(array(
    'bootstrap/bootstrap.min.css',
    'zodream/zodream.css'
));?>
</head>
<body>
