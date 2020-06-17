<?php
namespace Module\Trade\Domain\Migrations;

use Module\Trade\Domain\Model\RefundModel;
use Module\Trade\Domain\Model\TradeModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTradeTables extends Migration {

    public function up() {
        $this->append(TradeModel::tableName(), function (Table $table) {
            $table->setComment('支付系统');
            $table->set('id')->pk()->ai();
            $table->set('open_id')->int()->notNull();
            $table->set('buyer_id')->int()->notNull()->comment('买家');
            $table->set('seller_id')->int()->notNull()->comment('收款方');
            $table->set('out_trade_no')->varchar(64)->notNull()->comment('商户订单号,64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复');
            $table->set('subject')->varchar()->notNull();
            $table->set('body')->varchar()->defaultVal('')->comment('订单描述');
            $table->set('total_amount')->decimal(10, 2)->notNull()->comment('订单总金额');
            $table->set('operator_id')->varchar(28)->defaultVal('')->comment('商户操作员编号');
            $table->set('timeout_express')->varchar(6)->defaultVal('')->comment('该笔订单允许的最晚付款时间，逾期将关闭交易');
            $table->set('notify_url')->varchar()->defaultVal('')->comment('通知地址');
            $table->set('return_url')->varchar()->defaultVal('')->comment('返回地址');
            $table->set('passback_params')->varchar(512)->defaultVal('')->comment('公用回传参数，如果请求时传递了该参数，则返回给商户时会回传该参数。支付宝只会在同步返回（包括跳转回商户网站）和异步通知时将该参数原样返回');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->append(RefundModel::tableName(), function (Table $table) {
            $table->setComment('支付退款系统');
            $table->set('id')->pk()->ai();
            $table->set('trade_id')->int()->notNull();
            $table->set('out_request_no')->varchar(64)->notNull()->comment('标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传');
            $table->set('refund_reason')->varchar()->defaultVal('')->comment('退款的原因说明');
            $table->set('refund_amount')->decimal(10, 2)->notNull()->comment('需要退款的金额');
            $table->set('operator_id')->varchar(28)->defaultVal('')->comment('商户操作员编号');
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->timestamps();
        })->autoUp();
    }
}
