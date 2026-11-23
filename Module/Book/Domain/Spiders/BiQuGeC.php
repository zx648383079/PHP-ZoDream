<?php
declare(strict_types=1);
namespace Module\Book\Domain\Spiders;

class BiQuGeC extends BiQuGe {

    protected string $matchCatalog = '#^/?html/[\d_]+/[\d_]+/?$#i';

    protected string $matchContent = '#^/?html/[\d_]+/[\d_]+/\d+\.html$#i';

    protected string $matchList = '#<a[^<>]+href="(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}