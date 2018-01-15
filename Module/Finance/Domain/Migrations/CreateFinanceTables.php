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
    public function up() {
        Schema::createTable(MoneyAccountModel::tableName(), function(Table $table) {
            $table->setComment('资金账户');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(35)->notNull()->comment('账户名');
            $table->set('money')->decimal(10, 2)->notNull()
                ->defaultVal(0)->comment('可用金额');
            $table->set('frozen_money')->decimal(10, 2)->notNull()
                ->defaultVal(0)->comment('冻结金额');
            $table->set('remark')->text()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::createTable(FinancialProjectModel::tableName(), function(Table $table) {
            $table->setComment('理财项目');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(35)->notNull()->comment('配置项目');
            $table->set('alias')->varchar(50)->notNull()->comment('别名');
            $table->set('number')->decimal(10, 2)->notNull()->defaultVal(0)->comment('金额');
            $table->set('accounted_for')->decimal(10, 4)->comment('占比');
            $table->set('earnings')->decimal(10, 4)->comment('收益率/预估收益率');
            $table->set('start_at')->dateTime()->comment('起息日期');
            $table->set('end_at')->dateTime()->comment('到期日期');
            $table->set('earnings_number')->decimal(10, 2)->comment('到期收益');
            $table->set('product_id')->int()->comment('理财产品');
            $table->set('status')->bool()->defaultVal('1')->comment('0 已结束  1 进行中');
            $table->softDeletes()->comment('1 正常 0 删除');
            $table->set('color')->bool()->defaultVal(1)->comment('1 红色（赚） 0 绿（亏）');
            $table->set('remark')->text()->comment('备注');
            $table->timestamps();
        });
        Schema::createTable(FinancialProductModel::tableName(), function(Table $table) {
            $table->setComment('理财产品');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(50)->notNull();
            $table->set('status')->bool()->defaultVal('1')->comment('1 正常 0 删除');
            $table->set('remark')->text()->comment('备注');
            $table->timestamps();
        });
        Schema::createTable(LogModel::tableName(), function(Table $table) {
            $table->setComment('资金变动记录');
            $table->set('id')->pk()->ai();
            $table->set('type')->bool()->defaultVal('0')->comment('1 收入  0 支出');
            $table->set('money')->decimal(10, 2)->comment('金额');
            $table->set('frozen_money')->decimal(10, 2)->comment('冻结金额');
            $table->set('account_id')->int()->notNull()->comment('资金账户');
            $table->set('channel_id')->int()->defaultVal(0)->comment('支出时填写消费渠道');
            $table->set('project_id')->int()->defaultVal(0)->comment('收入时填写理财项目');
            $table->set('remark')->text()->comment('备注');
            $table->timestamps();
        });
        Schema::createTable(ConsumptionChannelModel::tableName(), function(Table $table) {
            $table->setComment('消费渠道');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(50)->notNull()->comment('消费渠道名称');
            $table->timestamps();
        });
        Schema::createTable(BudgetModel::tableName(), function(Table $table) {
            $table->setComment('预算计划');
            $table->set('id')->pk()->ai();
            $table->set('name')->varchar(50)->notNull()->comment('预算名');
            $table->set('budget')->decimal(10, 2)->comment('预算');
            $table->set('spent')->decimal(10, 2)->comment('花费');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(MoneyAccountModel::tableName());
        Schema::dropTable(FinancialProjectModel::tableName());
        Schema::dropTable(FinancialProductModel::tableName());
        Schema::dropTable(LogModel::tableName());
        Schema::dropTable(ConsumptionChannelModel::tableName());
        Schema::dropTable(BudgetModel::tableName());
    }
}