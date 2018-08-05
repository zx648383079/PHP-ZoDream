<?php
namespace Module\Book\Domain\Spiders;

class IBiQuGe extends BiQuGe {

    protected $matchCatalog = '#^/?[\d_]+/?$#i';

    protected $matchContent = '#^/?[\d_]+/\d+\.html$#i';

    protected $matchList = '#<a[^<>]+href="(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}