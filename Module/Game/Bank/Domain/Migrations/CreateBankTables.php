<?php
namespace Module\Game\Bank\Domain\Migrations;


use Module\Game\Bank\Domain\Model\BankLogModel;
use Module\Game\Bank\Domain\Model\BankProductModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateBankTables extends Migration {
    public function up() {
        $this->append(BankProductModel::tableName(), function (Table $table) {
            $table->setComment('理财产品表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('产品名');
            $table->set('min_amount')
                ->smallInt(4)->unsigned()->defaultVal(0)->comment('门槛');
            $table->set('cycle')->smallInt(4)
                ->unsigned()
                ->defaultVal(0)->comment('周期/天');
            $table->set('earnings')->int()
                ->unsigned()->defaultVal(0)->comment('收益率/10000');
            $table->set('risk')->int()
                ->unsigned()->defaultVal(0)->comment('风险系数/10000');
        })->append(BankLogModel::tableName(), function (Table $table) {
            $table->setComment('投资记录表');
            $table->set('id')->pk();
            $table->set('user_id')->int()->notNull()->unsigned();
            $table->set('product_id')->int()->notNull()->unsigned();
            $table->set('money')
                ->int()->unsigned()->notNull()->comment('金额');
            $table->set('real_money')
                ->int()->unsigned()->notNull()->comment('实际金额');
            $table->timestamp('end_at')->comment('到期时间');
            $table->set('earnings')->int()
                ->unsigned()->defaultVal(0)->comment('收益率/10000');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('状态');
            $table->timestamps();
        });
        parent::up();
    }
}