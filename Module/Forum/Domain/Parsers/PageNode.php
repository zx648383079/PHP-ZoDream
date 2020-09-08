<?php
/*
 * @Author: zodream
 * @Date: 2020-09-01 21:34:11
 * @LastEditors: zodream
 * @LastEditTime: 2020-09-08 22:38:14
 */
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;
/**
 * TODO 分页功能开发
 */
class PageNode extends Node {

    public function render($type = null) {
        $blocks = explode('<page/>', $this->attr('content'));

    }
}