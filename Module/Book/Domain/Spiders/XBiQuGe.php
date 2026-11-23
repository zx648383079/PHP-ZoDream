<?php
declare(strict_types=1);
namespace Module\Book\Domain\Spiders;

class XBiQuGe extends BiQuGe {

    protected string $matchCatalog = '#^/?[\d_]+/?$#i';

    protected string $matchContent = '#^/?[\d_]+/\d+\.html$#i';

    protected string $matchList = '#<a[^<>]+href="/([\d_]+)/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}