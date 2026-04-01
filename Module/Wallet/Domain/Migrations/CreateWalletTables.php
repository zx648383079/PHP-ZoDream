<?php
declare(strict_types=1);
namespace Module\Wallet\Domain\Migrations;

use Module\Wallet\Domain\Entities\AccountLogEntity;
use Module\Wallet\Domain\Entities\PayLogEntity;
use Module\Wallet\Domain\Entities\PaymentEntity;
use Module\Wallet\Domain\Entities\WalletAccountEntity;
use Module\Wallet\Domain\Entities\WalletEntity;
use Module\Wallet\Domain\Entities\WalletLogEntity;
use Module\Wallet\Domain\Entities\RefundEntity;
use Module\Wallet\Domain\Entities\TradeEntity;
use Zodream\Database\Model\Model;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateWalletTables extends Migration {

    public function up(): void {
        $this->append(TradeEntity::tableName(), function (Table $table) {
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
        })->append(RefundEntity::tableName(), function (Table $table) {
            $table->comment('支付退款系统');
            $table->id();
            $table->uint('trade_id');
            $table->string('out_request_no', 64)->comment('标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传');
            $table->string('refund_reason')->default('')->comment('退款的原因说明');
            $table->decimal('refund_amount', 10, 2)->comment('需要退款的金额');
            $table->string('operator_id', 28)->default('')->comment('商户操作员编号');
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(PayLogEntity::tableName(), function (Table $table) {
            $table->comment('支付记录');
            $table->id()->ai(10000001);
            $table->uint('payment_id');
            $table->string('payment_name', 30)->default('');
            $table->uint('type', 1)->default(0);
            $table->uint('user_id');
            $table->string('data')->default('')->comment('可以接受多个订单号');
            $table->uint('status', 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 10)->default('')->comment('货币');
            $table->decimal('currency_money', 10, 2)->default(0)->comment('货币金额');
            $table->string('trade_no', 100)->default('')->comment('外部订单号');
            $table->timestamp('begin_at')->comment('开始时间');
            $table->timestamp('confirm_at')->comment('确认支付时间');
            $table->timestamps();
        })->append(PaymentEntity::tableName(), function (Table $table) {
            $table->comment('第三方支付');
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('icon')->default('');
            $table->string('description')->default('');
            $table->string('settings')->default('');
        })->append(AccountLogEntity::tableName(), function (Table $table) {
            $table->comment('账户资金变动表');
            $table->id();
            $table->uint('user_id')->default(0);
            $table->uint('type', 1)->default(99);
            $table->uint('item_id')->default(0);
            $table->int('money')->comment('本次发生金额');
            $table->int('total_money')->comment('当前账户余额');
            $table->int('credits')->comment('本次发生金额');
            $table->int('total_credits')->comment('当前账户余额');
            $table->uint('status', 2)->default(0);
            $table->string('remark')->default('');
            $table->timestamps();
        })->append(WalletAccountEntity::tableName(), function (Table $table) {
            $table->comment('第三方电子账户，包括银行卡等');
            $table->id();
            $table->uint('type', 1)->default(99);
            $table->string('account');
            $table->string('password')->default('');
            $table->timestamp('expired_at')->default(0);
            $table->uint('status', 2)->default(0);
            $table->timestamps();
        })->append(WalletEntity::tableName(), function (Table $table) {
            $table->comment('账户资金表');
            $table->id();
            $table->int('money')->comment('金额');
            $table->int('credits')->comment('积分');
            $table->string('password')->comment('支付密码');
            $table->timestamps();
        })->append(WalletLogEntity::tableName(), function (Table $table) {
            $table->comment('记录账户资金变化的，不可更改');
            $table->id();
            $table->uint('user_id')->default(0);
            $table->int('money')->comment('金额');
            $table->int('credits')->comment('积分');
            $table->string('hash')->default('');
            $table->timestamp(Model::CREATED_AT);
        })->autoUp();
    }
}
