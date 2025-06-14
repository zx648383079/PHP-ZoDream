<?php
declare(strict_types=1);
namespace Module\Trade\Domain\Migrations;

use Module\Trade\Domain\Model\RefundModel;
use Module\Trade\Domain\Model\TradeModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTradeTables extends Migration {

    public function up(): void {
        $this->append(TradeModel::tableName(), function (Table $table) {
            $table->comment('支付系统');
            $table->id();
            $table->uint('open_id');
            $table->string('buyer_id', 32)->comment('买家');
            $table->string('seller_id', 32)->comment('收款方');
            $table->string('out_trade_no', 64)->comment('商户订单号,64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复');
            $table->string('subject');
            $table->string('body')->default('')->comment('订单描述');
            $table->decimal('total_amount', 10, 2)->comment('订单总金额');
            $table->string('operator_id', 28)->default('')->comment('商户操作员编号');
            $table->timestamp('time_expire')->comment('该笔订单允许的最晚付款时间，逾期将关闭交易');
            $table->string('notify_url')->default('')->comment('通知地址');
            $table->string('return_url')->default('')->comment('返回地址');
            $table->string('passback_params', 512)->default('')->comment('公用回传参数，如果请求时传递了该参数，则返回给商户时会回传该参数。支付宝只会在同步返回（包括跳转回商户网站）和异步通知时将该参数原样返回');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(RefundModel::tableName(), function (Table $table) {
            $table->comment('支付退款系统');
            $table->id();
            $table->uint('trade_id');
            $table->string('out_request_no', 64)->comment('标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传');
            $table->string('refund_reason')->default('')->comment('退款的原因说明');
            $table->decimal('refund_amount', 10, 2)->comment('需要退款的金额');
            $table->string('operator_id', 28)->default('')->comment('商户操作员编号');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->autoUp();
    }
}
