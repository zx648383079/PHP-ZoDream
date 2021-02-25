<?php
namespace Module\Trade\Domain\Migrations;

use Module\Trade\Domain\Model\RefundModel;
use Module\Trade\Domain\Model\TradeModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTradeTables extends Migration {

    public function up() {
        $this->append(TradeModel::tableName(), function (Table $table) {
            $table->comment('支付系统');
            $table->id();
            $table->uint('open_id');
            $table->uint('buyer_id')->comment('买家');
            $table->uint('seller_id')->comment('收款方');
            $table->column('out_trade_no')->varchar(64)->comment('商户订单号,64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复');
            $table->column('subject')->varchar();
            $table->column('body')->varchar()->default('')->comment('订单描述');
            $table->column('total_amount')->decimal(10, 2)->comment('订单总金额');
            $table->column('operator_id')->varchar(28)->default('')->comment('商户操作员编号');
            $table->column('timeout_express')->varchar(6)->default('')->comment('该笔订单允许的最晚付款时间，逾期将关闭交易');
            $table->column('notify_url')->varchar()->default('')->comment('通知地址');
            $table->column('return_url')->varchar()->default('')->comment('返回地址');
            $table->column('passback_params')->varchar(512)->default('')->comment('公用回传参数，如果请求时传递了该参数，则返回给商户时会回传该参数。支付宝只会在同步返回（包括跳转回商户网站）和异步通知时将该参数原样返回');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(RefundModel::tableName(), function (Table $table) {
            $table->comment('支付退款系统');
            $table->id();
            $table->uint('trade_id');
            $table->column('out_request_no')->varchar(64)->comment('标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传');
            $table->column('refund_reason')->varchar()->default('')->comment('退款的原因说明');
            $table->column('refund_amount')->decimal(10, 2)->comment('需要退款的金额');
            $table->column('operator_id')->varchar(28)->default('')->comment('商户操作员编号');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->autoUp();
    }
}
