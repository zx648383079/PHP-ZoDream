<?php
namespace Module\Tool;

use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $map->add(url('./home/url'), time());
        $map->add(url('./home/unicode'), time());
        $map->add(url('./home/base64'), time());
        $map->add(url('./home/ascii'), time());
        $map->add(url( './home/hex'), time());
        $map->add(url('./home/md5'), time());
        $map->add(url('./home/sha1'), time());
        $map->add(url('./home/password'), time());
        $map->add(url('./home/html'), time());
        $map->add(url('./home/js'), time());
        $map->add(url('./home/css'), time());
        $map->add(url('./home/json'), time());
        $map->add(url('./home/time'), time());
    }
}