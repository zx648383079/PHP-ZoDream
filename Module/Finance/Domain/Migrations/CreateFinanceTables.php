<?php
namespace Module\Finance\Domain\Migrations;

use Module\Finance\Domain\Model\BudgetModel;
use Module\Finance\Domain\Model\ConsumptionChannelModel;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\FinancialProductModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Schema;
use Zodream\Database\Schema\Table;

class CreateFinanceTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        $this->append(MoneyAccountModel::tableName(), function(Table $table) {
            $table->comment('资金账户');
            $table->id();
            $table->string('name', 35)->comment('账户名');
            $table->decimal('money', 10, 2)
                ->default(0)->comment('可用金额');
            $table->decimal('frozen_money', 10, 2)
                ->default(0)->comment('冻结金额');
            $table->bool('status')->default(1)->comment('1 正常 0 删除');
            $table->text('remark')->nullable()->comment('备注');
            $table->uint('user_id');
            $table->softDeletes();
            $table->timestamps();
        })->append(FinancialProjectModel::tableName(), function(Table $table) {
            $table->comment('理财项目');
            $table->id();
            $table->string('name', 35)->comment('配置项目');
            $table->string('alias', 50)->comment('别名');
            $table->decimal('money', 10, 2)->default(0)->comment('金额');
            $table->uint('account_id')->comment('账户');
            $table->decimal('earnings', 10, 4)->comment('收益率/预估收益率');
            $table->datetime('start_at')->nullable()->comment('起息日期');
            $table->datetime('end_at')->nullable()->comment('到期日期');
            $table->decimal('earnings_number', 10, 2)->comment('到期收益');
            $table->uint('product_id')->comment('理财产品');
            $table->bool('status')->default(1)->comment('0 已结束  1 进行中');
            $table->bool('color')->default(1)->comment('1 红色（赚） 0 绿（亏）');
            $table->text('remark')->nullable()->comment('备注');
            $table->uint('user_id');
            $table->softDeletes();
            $table->timestamps();
        })->append(FinancialProductModel::tableName(), function(Table $table) {
            $table->comment('理财产品');
            $table->id();
            $table->string('name', 50);
            $table->bool('status')->default(1)->comment('1 正常 0 删除');
            $table->text('remark')->nullable()->comment('备注');
            $table->uint('user_id');
            $table->timestamps();
        })->append(LogModel::tableName(), function(Table $table) {
            $table->comment('资金变动记录');
            $table->id();
            $table->uint('parent_id')->default(0);
            $table->uint('type', 1)
                ->default(1)->comment('1 收入  0 支出 2 借出 3 借入');
            $table->decimal('money', 10, 2)->default(0)->comment('金额');
            $table->decimal('frozen_money', 10, 2)->default(0)->comment('冻结金额');
            $table->uint('account_id')->comment('资金账户');
            $table->uint('channel_id')->default(0)->comment('支出时填写消费渠道');
            $table->uint('project_id')->default(0)->comment('收入时填写理财项目');
            $table->uint('budget_id')->default(0)->comment('支出时选择预算');
            $table->text('remark')->nullable()->comment('备注');
            $table->datetime('happened_at')->comment('发生时间');
            $table->string('out_trade_no', 100)->default('')
                ->comment('外部导入的交易单号');
            $table->uint('user_id');
            $table->string('trading_object', 100)
                ->default('')->comment('交易对象');
            $table->timestamps();
        })->append(ConsumptionChannelModel::tableName(), function(Table $table) {
            $table->comment('消费渠道');
            $table->id();
            $table->string('name', 50)->comment('消费渠道名称');
            $table->uint('user_id');
            $table->timestamps();
        })->append(BudgetModel::tableName(), function(Table $table) {
            $table->comment('预算计划');
            $table->id();
            $table->string('name', 50)->comment('预算名');
            $table->decimal('budget', 10, 2)->default(0)->comment('预算');
            $table->decimal('spent', 10, 2)->default(0)->comment('花费');
            $table->uint('cycle', 1)->default(0)->comment('周期');
            $table->uint('user_id');
            $table->softDeletes();
            $table->timestamps();
        })->autoUp();
    }
}