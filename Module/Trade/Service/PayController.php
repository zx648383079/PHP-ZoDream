<?php
namespace Module\Trade\Service;


class PayController extends Controller {

    public function indexAction(
        $out_trade_no,
        $subject,
        $total_amount,
        $notify_url,
        $return_url,
        $operator_id = null,
        $timeout_express = '90m',
        $body = null,
        $buyer_id = null,
        $seller_id = null,
        $passback_params = null) {

    }

    public function queryAction(
        $out_trade_no = null,
        $trade_no = null) {

    }
}