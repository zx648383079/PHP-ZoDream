<?php
namespace Module\Disk\Domain\Migrations;

use Module\Finance\Domain\Model\BankModel;
use Module\Finance\Domain\Model\BudgetModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyFormModel;
use Module\Finance\Domain\Model\MoneyModel;
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
        Schema::createTable(BankModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('name')->varchar(35)->notNull()->comment('配置项目');
            $table->set('alias')->varchar(50)->notNull()->comment('别名');
            $table->set('number')->decimal(10, 2)->notNull()->defaultVal(0)->comment('金额');
            $table->set('accounted_for')->decimal(10, 4)->comment('占比');
            $table->set('earnings')->decimal(10, 4)->comment('收益率/预估收益率');
            $table->set('start_at')->dateTime()->comment('起息日期');
            $table->set('end_at')->dateTime()->comment('到期日期');
            $table->set('earnings_number')->decimal(10, 2)->comment('到期收益');
            $table->set('money_form_id')->int()->comment('资金形态');
            $table->set('status')->bool()->defaultVal('1')->comment('0 已结束  1 进行中');
            $table->softDeletes()->comment('1 正常 0 删除');
            $table->set('color')->bool()->defaultVal(1)->comment('1 红色（赚） 0 绿（亏）');
            $table->set('remark')->text()->comment('备注');
            $table->timestamps();
        });
        Schema::createTable(MoneyModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('account')->varchar(50)->notNull();
            $table->set('type')->tinyint(1)->notNull()->defaultVal(0)->comment('类型 0 现金 1 定期 2 活期');
            $table->set('money')->decimal(10, 2)->comment('余额');
            $table->set('status')->bool()->defaultVal('1')->comment('1 正常 0 删除');
            $table->set('remark')->text()->comment('备注');
            $table->set('withdrawal_pass')->varchar(100)->comment('取款密码');
            $table->timestamps();
        });
        Schema::createTable(MoneyFormModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('name')->varchar(50)->notNull()->comment('资金形态名称');
            $table->set('status')->bool()->defaultVal('1')->comment('1 正常 0 删除');
            $table->set('remark')->text()->comment('备注');
            $table->timestamps();
        });
        Schema::createTable(LogModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('type')->bool()->defaultVal('0')->comment('1 收入  0 支出');
            $table->set('number')->decimal(10, 2)->comment('金额');
            $table->set('remark')->text()->comment('备注');
            $table->timestamps();
        });
        Schema::createTable(BudgetModel::tableName(), function(Table $table) {
            $table->set('id')->pk();
            $table->set('budget')->decimal(10, 2)->comment('预算');
            $table->set('spent')->decimal(10, 2)->comment('花费');
            $table->set('remain')->decimal(10, 2)->comment('剩余');
            $table->set('month')->dateTime();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropTable(BankModel::tableName());
        Schema::dropTable(MoneyModel::tableName());
        Schema::dropTable(MoneyFormModel::tableName());
        Schema::dropTable(LogModel::tableName());
        Schema::dropTable(BudgetModel::tableName());
    }
}