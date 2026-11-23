<?php
declare(strict_types=1);
namespace Module\Book\Domain\Spiders;

class SBiQuGe extends BiQuGe {

    protected string $matchCatalog = '#^/?read/\d+/\d+/?$#i';

    protected string $matchContent = '#^/?read/\d+/\d+/\d+\.html$#i';

    protected string $matchList = '#<a[^<>]+href="(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}