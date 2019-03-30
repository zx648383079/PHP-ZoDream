<?php
use Zodream\Service\Web;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Web(APP_DIR, 'Ueditor');
$app->autoResponse();