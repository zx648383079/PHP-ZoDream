<?php
namespace Module\Book\Domain\Spiders;

class SBiQuGe extends BiQuGe {

    protected $matchCatalog = '#^/?read/\d+/\d+/?$#i';

    protected $matchContent = '#^/?read/\d+/\d+/\d+\.html$#i';

    protected $matchList = '#<a[^<>]+href="(\d+)\.html"[^<>]*>([\S\s]+?)</a>#i';

}