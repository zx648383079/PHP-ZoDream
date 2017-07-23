<?php
defined('APP_DIR') || die();
use Zodream\Service\Routing\Url;
use Zodream\Service\Config;
use Zodream\Infrastructure\ObjectExpand\JsonExpand;
/** @var $this \Zodream\Domain\View\View */
$this->title = '后台管理系统';
?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <title>后台</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="/assets/css/zodream.css" rel="stylesheet">
        <link href="/assets/css/admin.css" rel="stylesheet">
    </head>
    <body>
        <div class="table">
            <div class="table-row">
                <div class="table-cell navbar">
                    <a class="navicon" href="javascript:;"><i class="fa fa-navicon"></i></a>
                    <ul class="nav-top">
                        
                    </ul>
                    <ul class="nav-bottom">
                    </ul>
                </div>
                <div class="table-cell">
                    <div class="zd-tab">
                        <div class="zd-tab-head has-over">
                            <i class="fa fa-backward"></i>
                            <div class="zd-tab-head-content">
                                <ul>
                                </ul>
                            </div>
                            <i class="fa fa-forward"></i>
                        </div>
                        <div class="zd-tab-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/pace.min.js"></script>
        <script src="/assets/js/jquery.navbar.min.js"></script>
        <script src="/assets/js/admin.min.js"></script>
        <script>
            $(document).ready(function() {
                window.navbar = $(".navbar").navbar(<?=JsonExpand::encode(Config::menu())?>);
                $(".navbar .navicon").click(function() {
                    $(".navbar").toggleClass("min");
                });
                var setMain = function() {
                    $(".table .zd-tab-body").height($(document).height() - 30);
                };
                setMain();
                $(window).resize(function() {
                    setMain();
                });
            });
        </script>
    </body>
</html>