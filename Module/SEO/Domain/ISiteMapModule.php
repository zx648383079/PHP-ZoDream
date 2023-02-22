<?php
declare(strict_types=1);
namespace Module\SEO\Domain;

interface ISiteMapModule {

    public function openLinks(SiteMap $map);
}