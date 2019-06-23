<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="0.1;url=<?=$this->text($url)?>">
    <title><?=__('loading...')?></title>
    <style>
    body {
        overflow: hidden;
        background: #f1f1f1
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow: hidden;
        animation-delay: 1s
    }

    .item-1 {
        width: 20px;
        height: 20px;
        background: #f583a1;
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
        background: #f583a1;
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
        background: #f583a1;
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
        background: #f583a1;
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
        background: #f583a1;
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
    <div class="container">
        <div class="item-1"></div>
        <div class="item-2"></div>
        <div class="item-3"></div>
        <div class="item-4"></div>
        <div class="item-5"></div>
    </div>
</body>
</html>