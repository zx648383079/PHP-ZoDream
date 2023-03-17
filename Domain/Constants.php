<?php
declare(strict_types=1);
namespace Domain;

final class Constants {
    const TYPE_USER = 1;

    const TYPE_USER_UPDATE = 5;

    const TYPE_USER_RECHARGE = 6; // 给账户充值

    const TYPE_BLOG = 10;
    const TYPE_BLOG_COMMENT = 11;

    const TYPE_BOOK = 14;

    const TYPE_EXAM_QUESTION = 20;
    const TYPE_EXAM_PAGE = 21;

    const TYPE_CMS_CONTENT = 23;
    const TYPE_DEMO = 24;

    const TYPE_FORUM_THREAD = 27;
    const TYPE_FORUM_POST = 28;

    const TYPE_SEARCH_SITE = 30;
    const TYPE_SEARCH_PAGE = 31;

    const TYPE_MICRO_BLOG = 33;
    const TYPE_MICRO_COMMENT = 34;

    const TYPE_SHOP_GOODS = 35;
    const TYPE_SHOP_AD = 36;

    const TYPE_VIDEO = 40;
    const TYPE_VIDEO_MUSIC = 41;

    const TYPE_WECHAT_ACCOUNT = 45;
    const TYPE_WECHAT_MEDIA = 46;

    const TYPE_APP_STORE = 50;
    const TYPE_RESOURCE_STORE = 55;

    const TYPE_SYSTEM = 80;

    const TYPE_ROLE = 83;
    const TYPE_ROLE_PERMISSION = 84;
    const TYPE_SYSTEM_FRIEND_LINK = 84;
    const TYPE_SYSTEM_FEEDBACK = 85;
}