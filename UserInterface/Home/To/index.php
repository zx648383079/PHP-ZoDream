<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

?>
<!DOCTYPE html>
<html lang="<?=app()->getLocale()?>">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php if($autoJump):?>
        <meta http-equiv="refresh" content="0.1;url=<?=$encodeUrl?>">
    <?php endif;?>
    <title><?=__('loading...')?></title>
    <style>
    body {
        overflow: hidden;
        background: #f1f1f1;
        padding: 0;
        margin: 0;
    }
    .flex-center {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .jump-tip {
        display: flex;
        width: 100%;
        max-width: 30rem;
        position: relative;
    }
    .jump-tip span {
        flex: 1;
        display: inline-block;
        word-break: break-all;
        background-color: #ccc;
        border-radius: .25rem 0 0 .25rem;
        padding: .8rem;
    }
    .jump-tag {
        position: absolute;
        left: 0;
        bottom: -2rem;
        color: white;
        padding: .4rem 1rem;
        opacity: .5;
        user-select: none;
    }

    .jump-btn {
        display: flex;
        align-items: center;
        background-color: #ee8c68;        
        color: white;
        padding: .4rem;
        border-radius: 0 .25rem .25rem 0;
        word-break: keep-all;
        text-decoration: none;
    }
    .jump-btn-danger,
    .jump-tag {
        background-color: rgb(222, 53, 69);
    }

    .loading-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 16rem;
        height: 6rem;
        overflow: hidden;
        animation-delay: 1s;
    }

    .item-1 {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eed968;
        margin: 7px;
        display: flex;
        justify-content: center;
        align-items: center
    }

    @keyframes scale {
        0% {
            transform: scale(1)
        }

        50%,
        75% {
            transform: scale(2.5)
        }

        78%,
        100% {
            opacity: 0
        }
    }

    .item-1:before {
        content: '';
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eed968;
        opacity: .7;
        animation: scale 2s infinite cubic-bezier(0, 0, 0.49, 1.02);
        animation-delay: 200ms;
        transition: .5s all ease;
        transform: scale(1)
    }

    .item-2 {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eece68;
        margin: 7px;
        display: flex;
        justify-content: center;
        align-items: center
    }

    @keyframes scale {
        0% {
            transform: scale(1)
        }

        50%,
        75% {
            transform: scale(2.5)
        }

        78%,
        100% {
            opacity: 0
        }
    }

    .item-2:before {
        content: '';
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eece68;
        opacity: .7;
        animation: scale 2s infinite cubic-bezier(0, 0, 0.49, 1.02);
        animation-delay: 400ms;
        transition: .5s all ease;
        transform: scale(1)
    }

    .item-3 {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eec368;
        margin: 7px;
        display: flex;
        justify-content: center;
        align-items: center
    }

    @keyframes scale {
        0% {
            transform: scale(1)
        }

        50%,
        75% {
            transform: scale(2.5)
        }

        78%,
        100% {
            opacity: 0
        }
    }

    .item-3:before {
        content: '';
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eec368;
        opacity: .7;
        animation: scale 2s infinite cubic-bezier(0, 0, 0.49, 1.02);
        animation-delay: 600ms;
        transition: .5s all ease;
        transform: scale(1)
    }

    .item-4 {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eead68;
        margin: 7px;
        display: flex;
        justify-content: center;
        align-items: center
    }

    @keyframes scale {
        0% {
            transform: scale(1)
        }

        50%,
        75% {
            transform: scale(2.5)
        }

        78%,
        100% {
            opacity: 0
        }
    }

    .item-4:before {
        content: '';
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #eead68;
        opacity: .7;
        animation: scale 2s infinite cubic-bezier(0, 0, 0.49, 1.02);
        animation-delay: 800ms;
        transition: .5s all ease;
        transform: scale(1)
    }

    .item-5 {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #ee8c68;
        margin: 7px;
        display: flex;
        justify-content: center;
        align-items: center
    }

    @keyframes scale {
        0% {
            transform: scale(1)
        }

        50%,
        75% {
            transform: scale(2.5)
        }

        78%,
        100% {
            opacity: 0
        }
    }

    .item-5:before {
        content: '';
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #ee8c68;
        opacity: .7;
        animation: scale 2s infinite cubic-bezier(0, 0, 0.49, 1.02);
        animation-delay: 1000ms;
        transition: .5s all ease;
        transform: scale(1)
    }
    </style>
</head>
<body>
    <div class="flex-center">
        <div class="jump-tip">
            <span><?=$url?></span>
            <?php if($isValid):?>
            <a class="jump-btn" href="<?=$encodeUrl?>" rel="noopener nofollow">GO</a>
            <?php else:?>
            <a class="jump-btn jump-btn-danger" href="<?=$encodeUrl?>" rel="noopener nofollow" title="<?=__('This URL is suspicious, maybe multiple jumps')?>">GO</a>
            <div class="jump-tag" title="<?=__('This URL is suspicious, maybe multiple jumps')?>"><?=__('Suspicious URL')?></div>
            <?php endif;?>
        </div>
        <div class="loading-container">
            <div class="item-1"></div>
            <div class="item-2"></div>
            <div class="item-3"></div>
            <div class="item-4"></div>
            <div class="item-5"></div>
        </div>
    </div>
</body>
</html>