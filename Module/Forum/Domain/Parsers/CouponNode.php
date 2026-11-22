<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Parsers;

use Module\Template\Domain\Weights\Node;
use Zodream\Helpers\Time;

class CouponNode extends Node {

    public function render(string $type = ''): mixed {
        $content = $this->attr('content');
        $val = $this->attr('expire');
        $expire = empty($val) ? 0 : strtotime($val);
        $val = $this->attr('amount');
        $amount = is_numeric($val) ? intval($val) : -1;
        if ($type === 'json') {
            return [
                'tag' => 'coupon',
                'content' => $content,
                'expire' => $expire,
                'amount' => $amount
            ];
        }
        $cls = '';
        if (($expire > 0 && $expire < time()) || $amount === 0) {
            $cls = ' --with-expire';
        }
        $alt = '剩余次数：';
        if ($amount < 0) {
            $alt .= '不限';
        } else {
            $alt .= $amount;
        }
        if ($expire > 0) {
            $alt = '，截至日期：'.Time::format($expire);
        }
        return <<<HTML
<div class="coupon-open-node{$cls}" title="{$alt}">
    <div class="node-body">{$content}</div>
    <i class="fa fa-copy copy-icon" data-action="copy"></i>
</div>
HTML;
    }
}