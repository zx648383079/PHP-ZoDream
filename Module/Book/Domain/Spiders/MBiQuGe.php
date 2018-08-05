<?php
namespace Module\Book\Domain\Spiders;


class MBiQuGe extends BiQuGe {

    protected $matchCatalog = '#^/?\d+/\d+/?$#i';

    protected $matchContent = '#^/?\d+/\d+/\d+\.html$#i';

    protected $matchList = '#<a[^<>]+href="/\d+/(\d+)/(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}