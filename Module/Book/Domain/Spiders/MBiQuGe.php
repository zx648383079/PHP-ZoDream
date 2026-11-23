<?php
declare(strict_types=1);
namespace Module\Book\Domain\Spiders;


class MBiQuGe extends BiQuGe {

    protected string $matchCatalog = '#^/?\d+/\d+/?$#i';

    protected string $matchContent = '#^/?\d+/\d+/\d+\.html$#i';

    protected string $matchList = '#<a[^<>]+href="/\d+/(\d+)/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}