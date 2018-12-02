<?php
namespace Module\Book\Domain\Spiders;

class BiQuGeC extends BiQuGe {

    protected $matchCatalog = '#^/?html/[\d_]+/[\d_]+/?$#i';

    protected $matchContent = '#^/?html/[\d_]+/[\d_]+/\d+\.html$#i';

    protected $matchList = '#<a[^<>]+href="(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}